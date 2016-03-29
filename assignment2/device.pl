#! /usr/local/bin/perl
#use strict;
#use warnings;
use DBI;
use Data::Dumper qw(Dumper);
use Net::SNMP qw(:snmp);
use RRD::Simple ();

#database connect
require "dbpath.pl";
require "$realpath";
$dsn = "DBI:mysql:database=$database;host=$hostname;port=$port";
$dbh = DBI->connect($dsn, $username, $password,{RaiseError => 1});

#retrivind data from database
my $sth = $dbh->prepare("SELECT IP, PORT, COMMUNITY,interfaces FROM assign2_system ");
$sth->execute() or die $DBI::errstr;

my $x=0;
my %table;
while (my @row = $sth->fetchrow_array()) 
{
my @oid_in;
my @oid_out;
my @oid_all;
my ($ip, $port, $community,$interfaces ) = @row;
$table{"$ip:$port:$community"}{'ip'}="$ip";
$table{"$ip:$port:$community"}{'port'}="$port";
$table{"$ip:$port:$community"}{'community'}="$community";

my @intf=split('&',$interfaces);
my $i=0;
foreach(@intf)
{
$table{"$ip:$port:$community"}{interface}{$_}=$_;
push(@oid_in,"1.3.6.1.2.1.2.2.1.10.$_");
push(@oid_out,"1.3.6.1.2.1.2.2.1.16.$_");
 }
push(@oid_all,@oid_in,@oid_out);

#session creation
my ($session, $error) = Net::SNMP->session(
      -hostname    => $ip,
      -port        => $port,
      -community   => $community,
      -nonblocking => 1,
      -translate   => [-octetstring => 0],
      -version     => 'snmpv2c',
   );
if (!defined $session) {
      printf "ERROR: %s.\n", $error;
      exit 1;
   }
if(@oid_all)
{
my $result_ifType = $session->get_request(
                                           -varbindlist      => [splice @oid_all, 0, 40],
                                           -callback        => [ \&sub_octet, $ip, $port, $community] ,
				                      );
if (!defined($result_ifType))
{
printf "ERROR: Failed to queue get request for host '%s': %s.\n",
$session->hostname(), $session->error();
}
else{
	print "req sent";
}

snmp_dispatcher();
}
}


foreach my $p (keys %table)
{
my @asd = keys (%{$table{$p}{"byte"}});
foreach my $l (@asd)
{
if(($table{"$p"}{"byte"}{$l}{"1.3.6.1.2.1.2.2.1.10.$l"})==noSuchInstance)
{
($table{"$p"}{"byte"}{$l}{"1.3.6.1.2.1.2.2.1.10.$l"})=0;
}
if(($table{"$p"}{"byte"}{$l}{"1.3.6.1.2.1.2.2.1.16.$l"})==noSuchInstance)
{
($table{"$p"}{"byte"}{$l}{"1.3.6.1.2.1.2.2.1.16.$l"})=0;
}
}
}
#print Dumper \%table;
# rrd database creation and update
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
push(@rrd,("bytesIntotal" => "COUNTER"), ("bytesOuttotal" => "COUNTER"));
}
#print Dumper \@rrd;
$rrd->create("$t.rrd", "year",@rrd);
}
 foreach $x (@asd)
{
 # Put some arbitary data values in the RRD file for the same
 # 3 data sources called bytesIn, bytesOut and faultsPerSec.
push(@rrd1,("bytesIn$x" => $table{$t}{"byte"}{$x}{"1.3.6.1.2.1.2.2.1.10.$x"}), ("bytesOut$x" => $table{$t}{"byte"}{$x}{"1.3.6.1.2.1.2.2.1.16.$x"}));
$inagg=$inagg+$table{"$t"}{"byte"}{$x}{"1.3.6.1.2.1.2.2.1.10.$x"};
$outagg=$outagg+$table{"$t"}{"byte"}{$x}{"1.3.6.1.2.1.2.2.1.16.$x"};
}
$table{$t}{"inall"}="$inagg";
$table{$t}{"outall"}="$outagg";
push(@rrd1,("bytesIntotal" => $table{$t}{"inall"}), ("bytesOuttotal" => $table{$t}{"outall"}));
my $x1=time();

			$rrd->update("$t.rrd",$x1,@rrd1);		
			
print "rrd  uppdated";
}
}


#sub routines
sub sub_octet
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

}
															
