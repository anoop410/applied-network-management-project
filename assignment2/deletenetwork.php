<!DOCTYPE HTML>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Free Responsive Template #4 - Quality Co</title>
<!-- css3-mediaqueries.js for IE8 or older -->
<!--[if lt IE 9]>
	<script src="http://css3-mediaqueries-js.googlecode.com/svn/trunk/css3-mediaqueries.js"></script>
<![endif]-->
<link href="css/styles.css" rel="stylesheet" type="text/css">
</head>


<body>

  <header class="container">
    <h1>Assignment2</h1>
    <nav>
        <ul id="navlist">
          <li><a href="index.php">Add a Device</a></li>
            <li><a href="delete.php">Delete Device</a></li>
		 <li><a href="monitorboth.php">Details</a></li>
        </ul>
    </nav>
  </header>
  
    <section class="container" style="text-align: center">
<font face="Arial" size="3">
            	    	
        <td style="background-color:#eeeeee;height:600px;width:2000px;vertical-align:top;">
        <center><br>Select the device to be deleted<br>
        <?php
         include "db.php";
         
         $database = mysql_connect("$host:$port",$userid,$passwd)
          or die ("Unable to connect to the Database");
         $connect = (mysql_select_db("$name",$database))
          or die ("Database could not be selected");
          
         
          
         $q1 = mysql_query("SELECT *FROM assign2_system");
         echo "<table border=1>
               <tr><td>S.No</td><td>IP</td><td>COMMUNITY</td><td>PORT</td>";
         $i=1;                
         while($row=mysql_fetch_array($q1)):
          {
            $ID = $row['ID']; $IP = $row['IP']; $PORT = $row['PORT']; $COMMUNITY = $row['COMMUNITY'];
            $device = "$IP:$COMMUNITY:$PORT";
            echo "<tr><td>" . $i . "</td><td>" . $row['IP'] . "</td><td>" . "<a href='deletenetwork1.php?ID=$ID&device=$device'>" . $row['COMMUNITY'] . "</td><td>" . $row['PORT'] . "</td></tr>";
           $i++; 
          }
          endwhile;
          echo "</table>";
          mysql_close($database);
        ?>    	    
        </br></td></center>    	    
        
        </table>
        </div>
        </body>
</html>        
