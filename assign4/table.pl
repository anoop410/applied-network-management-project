#!/usr/bin/perl

use DBI;
use Cwd;
require "dbpath.pl";
require "$realpath";

	sub table()
{
	#database connection

         $dsn = "DBI:mysql:database=$database;host=$hostname;port=$port";
         $dbh = DBI->connect($dsn, $username, $password,{RaiseError => 1});

	$uth = $dbh->prepare("CREATE TABLE IF NOT EXISTS uptime (id int (11) NOT NULL AUTO_INCREMENT, IP tinytext NOT NULL, PORT int (11) NOT NULL, COMMUNITY tinytext NOT NULL, Uptime varchar(255) NOT NULL, sent int (11) NOT NULL, lost int (11) NOT NULL, PRIMARY KEY (id)) ENGINE=InnoDB DEFAULT CHARSET= latin1 AUTO_INCREMENT=1;");
	$uth->execute() or die $DBI::errstr; 
	
	$fth = $dbh->prepare("INSERT INTO uptime (id,IP,PORT,COMMUNITY) SELECT DEVICES.id, DEVICES.IP, DEVICES.PORT, DEVICES.COMMUNITY FROM DEVICES ON DUPLICATE KEY UPDATE sent=0,lost=0");		
	$fth->execute() or die $DBI::errstr;
}
1;

