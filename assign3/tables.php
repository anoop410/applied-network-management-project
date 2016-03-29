<?php
    include 'db.php';
$con = mysql_connect($hostname, $username, $password)
        or die("Unable to connect to MySQL");
//select a database to work with
mysql_select_db("$database", $con)
        or die("Could not select $database");
	
	    $sql="CREATE TABLE IF NOT EXISTS `trap_db` (
		  `ID` int(11) NOT NULL AUTO_INCREMENT,
		  `FQDN` varchar(50) NOT NULL,
		  `PrevStatus` varchar(50) NOT NULL,
		  `CurrentStatus` varchar(50) NOT NULL,
		  `PrevTime` tinytext NOT NULL,
		  `CurrentTime` tinytext NOT NULL,
		   PRIMARY KEY (ID)
		) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ";
		
          

            if (!mysql_query($sql))
	    {
	      echo "Error creating table: " . mysql_error($con) ."\n";
	    }
	    

               $sql="CREATE TABLE IF NOT EXISTS `trap_alert`(
                  `ID` int(11) NOT NULL AUTO_INCREMENT,
                  `IP` tinytext NOT NULL,
		  `PORT` int(11) NOT NULL,
		  `COMMUNITY` varchar(150) NOT NULL,
		   PRIMARY KEY (ID)
		) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ";
	    

            if (!mysql_query($sql))
	    {
	      echo "Error creating table: " . mysql_error($con) ."\n";
	    }
 mysql_close($con);
	    
?>
