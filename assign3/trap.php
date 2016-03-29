<!DOCTYPE html>
<html>
	<head>

		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<link rel="icon" href="favicon.ico" type="image/x-icon">
		<link rel = "stylesheet" href = "bootstrap/css/bootstrap.min.css">
		<link rel = "stylesheet" href = "bootstrap/css/bootstrap-theme.min.css">
		<link rel = "stylesheet" href = "bootstrap/css/style.css">

		<script type="text/javascript" src="./java.js"></script>
		<script type="text/JavaScript">

			var auto_refresh = setInterval(
			function ()
			{
			$('#tweet').fadeIn("slow");
			}, 5000); // refresh every 5000 milliseconds

		</script>

		<title>
			Assignment 4 Uptime of DEVICES  |  Home
		</title>
	</head>


	<body>


<!-- Navigation panel -->
<div class = "col-md-1" style = "padding: 0; border-right: solid 1px black; height: 1241px;">
	<div class = "container-fluid" style = "margin: 0; padding: 0;">
		<ul class = "nav nav-pills nav-stacked">
			<li role = "presentation" class = "active"><a href = "index.php">Home</a></li>
		</ul>
	</div>
</div>
<center>
<h2>Assignment 3 </h2>
</center>
         <?php
         
         include "db.php";
$con = mysql_connect($hostname, $username, $password)
        or die("Unable to connect to MySQL");
//select a database to work with
mysql_select_db("$database", $con)
        or die("Could not select $database");

         
       $IP = $_POST['IP'];
        $PORT = $_POST['PORT'];
        $COMMUNITY = $_POST['COMMUNITY'];
         
        $q1 = "SELECT IP FROM `trap_alert` WHERE `ID` = 1 ";
        $result = mysql_query($q1);
        $rows = mysql_num_rows($result);
         
         if($rows > 0){
              $q3 = "UPDATE `trap_alert` SET `IP`='$IP',`PORT`='$PORT',`COMMUNITY`='$COMMUNITY' WHERE `ID`=1";
               mysql_query($q3);
               echo "<br>Details of manager are modified";
                  } 
        if($rows==0){    
             $q2 = "INSERT INTO `trap_alert` (`IP`,`PORT`,`COMMUNITY`) VALUES ('$IP','$PORT','$COMMUNITY')";
             mysql_query($q2);
            echo "<br>Details of manager are added to send trap";
              }
                  
                  
        ?>   
        </body>
</html>        
