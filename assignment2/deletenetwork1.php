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
            	    	
        <td style="background-color:#eeeeee;height:600px;width:2000px;vertical-align:top;">
        <center><br>
        <?php
          include "db.php";
          
          $database=mysql_connect("$host:$port",$userid,$passwd)
           or die ("Could not connect to the Database");
          $connect=mysql_select_db("$name",$database)
           or die ("Database cannot be selected");
           
          $ID=$_GET['ID'];
          $device = $_GET['device'];
          
          $q1=mysql_query("DELETE FROM assign2_system WHERE ID=$ID");
          
          if(!$q1)
           {
            die ("ERROR: " . mysql_error());
           }
          else
           {
            echo "<br><br>Device is deleted<br>";
           }
           
           mysql_close($database);
           $path = dirname(__FILE__);
           unlink("$path/$device.rrd");
        ?>
            
        </br></td></center>    	    
        
        </table>
        </div>
        </body>
</html>        
