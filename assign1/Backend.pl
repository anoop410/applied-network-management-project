#! /usr/local/bin/perl

#use strict;
#use warnings;
require "dbpath.pl";
require "$realpath";
use DBI;
use Data::Dumper qw(Dumper);
use Net::SNMP qw(:snmp);
use RRD::Simple ();

my $OID_ifTable = '1.3.6.1.2.1.2.2.1.1';
my $OID_ifType = '1.3.6.1.2.1.2.2.1.3';
my $OID_ifOperStatus = '1.3.6.1.2.1.2.2.1.8'; 
my $OID_ifSpeed = '1.3.6.1.2.1.2.2.1.5';

my %data;
my %hash;
#database connection 
$dsn = "DBI:mysql:database=$database;host=$hostname;port=$port";
$dbh = DBI->connect($dsn, $username, $password,{RaiseError => 1});

my $sth= $dbh->do("CREATE TABLE IF NOT EXISTS mrtg_system
(IP varchar(255) NOT NULL ,
PORT int NOT NULL,
COMMUNITY varchar(255) NOT NULL,
sysuptime TINYTEXT NOT NULL ,
syscontact TINYTEXT NOT NULL,
syslocation TINYTEXT NOT NULL ,
sysdescr TINYTEXT NOT NULL,
sysname TEXT NOT NULL,
interfaces TEXT NOT NULL,
UNIQUE KEY(IP,PORT,COMMUNITY)
) ;");

#retrivind data from database
$sth = $dbh->prepare("SELECT * FROM DEVICES");
$sth->execute() or die $DBI::errstr;
while (@row=$sth->fetchrow) {
my $sth2 = $dbh->prepare("INSERT IGNORE INTO mrtg_system (IP,PORT,COMMUNITY) VALUES ('@row[1]','@row[2]','@row[3]')");
$sth2->execute() or die $DBI::errstr;
$sth2->finish();
}
$sth->finish();
#copy device table to hash
my $data = $dbh->selectall_hashref("SELECT * FROM DEVICES","id");
#print Dumper \%$data;
foreach(keys (%{$data}))
{
  $id=$data->{$_}->{id};
  $ip=$data->{$_}->{IP};
  $community=$data->{$_}->{COMMUNITY};
  $port=$data->{$_}->{PORT};
$table{"$ip:$port:$community"}{'ip'}="$ip";
$table{"$ip:$port:$community"}{'port'}="$port";
$table{"$ip:$port:$community"}{'community'}="$community";

my ($session, $error) = Net::SNMP->session(
      -hostname    => $ip,
      -port        => $port,
      -community   => $community,
      -nonblocking => 1,
      -translate   => [-octetstring => 0],
      -version     => 'snmpv2c',
   );
$hash{"$ip:$port:$community"}{"session"}=$session;

   if (!defined $session) {
      printf "ERROR: %s.\n", $error;
      exit 1;
   }

   my %table; 

if (!defined $session->get_table(-baseoid  => $OID_ifTable,
                                 -callback => [\&print_interface_table,$ip,$port,$community],))
{
   printf "ERROR: %s.\n", $session->error();
}
 if (!defined $session) {
               printf "ERROR: %s.\n", $error;
      exit 1;
      }

}


snmp_dispatcher();
# concetenation of oids
foreach $t (keys %table)
{
@asd = keys (%{$table{$t}{"interfaces"}});
foreach $x (@asd)
{
$type=("1.3.6.1.2.1.2.2.1.3.$x");
$table{"$t"}{"unfil"}{$type}=$type;

$oper=("1.3.6.1.2.1.2.2.1.8.$x");
$table{"$t"}{"unfil"}{$oper}=$oper;

$speed=("1.3.6.1.2.1.2.2.1.5.$x");
$table{"$t"}{"unfil"}{$speed}=$speed;
}

@oids = keys (%{$table{$t}{"unfil"}});
if(@oids)
{
if (!defined $hash{$t}{"session"}) {
               printf "ERROR: %s.\n", $error;
      exit 1;
      }
while(@oids)
{

if (!defined $hash{$t}{"session"}->get_request(
         -varbindlist => [splice @oids, 0, 40],
         -callback    => [ \&oid_request,$table{$t}{"ip"},$table{$t}{"port"},$table{$t}{"community"}]))
{
   printf "ERROR: %s.\n", $hash{$t}{"session"}->error();
}
}
}
}

snmp_dispatcher();

foreach $t (keys %table)
{
  @req = keys (%{$table{$t}{"request"}});
foreach $x (@req)
{
if ($table{$t}{"request"}{$x}{"1.3.6.1.2.1.2.2.1.3.$x"}!=24&&$table{$t}{"request"}{$x}{"1.3.6.1.2.1.2.2.1.5.$x"}>0&&$table{$t}{"request"}{$x}{"1.3.6.1.2.1.2.2.1.8.$x"}==1)
{
$type=("1.3.6.1.2.1.2.2.1.10.$x");
$table{$t}{"filt"}{$x}=$x;
$table{$t}{"unbyte"}{$type}=$type;
$type=("1.3.6.1.2.1.2.2.1.16.$x");
$table{$t}{"unbyte"}{$type}=$type;
}
}
}

# get request for bytes in and out

foreach $t (keys %table)
{
@oids= keys (%{$table{$t}{"unbyte"}});
if(@oids)
{
if (!defined $hash{$t}{"session"}) {
               printf "ERROR: %s.\n", $error;
      exit 1;
      }
while(@oids)
{

if (!defined $hash{$t}{"session"}->get_request(
         -varbindlist => [splice @oids, 0, 40],
         -callback    => [ \&oid_bytes,$table{$t}{"ip"},$table{$t}{"port"},$table{$t}{"community"}]))
{
   printf "ERROR: %s.\n", $hash{$t}{"session"}->error();
}
}
}
}
snmp_dispatcher();

# system details 
foreach $t (keys %table)
{
@ft= keys (%{$table{$t}{"filt"}});
if (@ft)
{
$intf=join(':',@ft);
$table{$t}{"interfaces"}=$intf;
my @system = ("1.3.6.1.2.1.1.3.0","1.3.6.1.2.1.1.4.0","1.3.6.1.2.1.1.5.0","1.3.6.1.2.1.1.6.0","1.3.6.1.2.1.1.2.0");
if (!defined $hash{$t}{"session"}) {
               printf "ERROR: %s.\n", $error;
      exit 1;
      }
while(@system)
{

if (!defined $hash{$t}{"session"}->get_request(
         -varbindlist => [splice @system, 0, 40],
         -callback    => [ \&system_details,$table{$t}{"ip"},$table{$t}{"port"},$table{$t}{"community"}]))
{
   printf "ERROR: %s.\n", $hash{$t}{"session"}->error();
}
}
}
}
snmp_dispatcher();

foreach $t (keys %table)
{
if(keys (%{$table{$t}{"filt"}}))
{
$sysuptime=$table{$t}{"1.3.6.1.2.1.1.3.0"};
$syscontact=$table{$t}{"1.3.6.1.2.1.1.4.0"};
$syslocation=$table{$t}{"1.3.6.1.2.1.1.6.0"};
$sysname=$table{$t}{"1.3.6.1.2.1.1.5.0"};
$interfaces=$table{$t}{"interfaces"};
$sysdescr=$table{$t}{"1.3.6.1.2.1.1.2.0"};
$ip =$table{$t}{"ip"};
$port=$table{$t}{"port"};
$community=$table{$t}{"community"};
my $sth7= $dbh->prepare("UPDATE mrtg_system SET   sysuptime='$sysuptime',syscontact ='$syscontact',syslocation='$syslocation',sysname='$sysname',interfaces='$interfaces',sysdescr='$sysdescr' WHERE IP = '$ip' AND PORT='$port' AND COMMUNITY='$community' ");
$sth7->execute() or die $DBI::errstr;
$sth7->finish();
}
}

#print Dumper \%table;

# Create an interface object
foreach $t (keys %table)
{
 my $rrd = RRD::Simple->new( file => "$t.rrd" );
 
 # Create a new RRD file with 3 data sources called
 # bytesIn, bytesOut and faultsPerSec.
my @asd = keys (%{$table{$t}{"byte"}});
my @rrd;
if(@asd)
{
if(! -e "$t.rrd" )
{
foreach $x (@asd)
{
push(@rrd,("bytesIn$x" => "COUNTER"),("bytesOut$x" => "COUNTER"));
}
$rrd->create("$t.rrd", "mrtg",@rrd);
}
 foreach $x (@asd)
{
 # Put some arbitary data values in the RRD file for the same
 # 3 data sources called bytesIn, bytesOut and faultsPerSec.
push(@rrd1,("bytesIn$x" => $table{$t}{"byte"}{$x}{"1.3.6.1.2.1.2.2.1.10.$x"}), ("bytesOut$x" => $table{$t}{"byte"}{$x}{"1.3.6.1.2.1.2.2.1.16.$x"}));
}
my $x1=time();

			$rrd->update("$t.rrd",$x1,@rrd1);		
			
print "rrd  uppdated";
}
}

#call back routines
sub system_details
{
my ($session,$ip,$port,$community) = @_;
if (!defined $session->var_bind_list()) {
      printf "ERROR: %s.\n", $session->error();
   } else {
      for ($session->var_bind_names()) {
        # printf "%s => %s\n", $_, $session->var_bind_list()->{$_};
         $table{"$ip:$port:$community"}{$_}=$session->var_bind_list()->{$_};
      }
   }

return;
}

sub oid_bytes
{
   my ($session,$ip,$port,$community) = @_;
if (!defined $session->var_bind_list()) {
      printf "ERROR: %s.\n", $session->error();
   } else {
      for ($session->var_bind_names()) {
        # printf "%s => %s\n", $_, $session->var_bind_list()->{$_};
my @sp=split('\.',$_);
         $table{"$ip:$port:$community"}{'byte'}{$sp[10]}{$_}=$session->var_bind_list()->{$_};
      }
   }

return;
} 

sub oid_request
{
   my ($session,$ip,$port,$community) = @_;
if (!defined $session->var_bind_list()) {
      printf "ERROR: %s.\n", $session->error();
   } else {
      for ($session->var_bind_names()) {
        # printf "%s => %s\n", $_, $session->var_bind_list()->{$_};
my @sp=split('\.',$_);
         $table{"$ip:$port:$community"}{'request'}{$sp[10]}{$_}=$session->var_bind_list()->{$_};
      }
   }

return;
}

sub print_interface_table
{
   my ($session,$ip,$port,$community) = @_;

   if (!defined $session->var_bind_list()) {
      printf "ERROR: %s.\n", $session->error();
   } else {
      for ($session->var_bind_names()) {
        # printf "%s => %s\n", $_, $session->var_bind_list()->{$_};

         $table{"$ip:$port:$community"}{'interfaces'}{$session->var_bind_list()->{$_}}=$session->var_bind_list()->{$_};
      }
   }
   return;
}

