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
			$('#tweet').load('status_panel.php').fadeIn("slow");
			}, 5000); // refresh every 5000 milliseconds

		</script>
                <title>
			Assignment 4 Uptime of DEVICE  |  uptime
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
<div class="col-md-2"style = "padding: 0;">
<h2>Details of the Device</h2>
<?php
include "db.php";
$id=$_GET['key'];
echo $id; 
   $conn = mysql_connect($hostname, $username, $password, $port);
   
   if(! $conn )
   {
      die('Could not connect: ' . mysql_error());
   }
 $sql = "SELECT * FROM uptime WHERE id='$id'";
   mysql_select_db($database);
   $retval = mysql_query( $sql, $conn );
 if(! $retval )
   {
      die('Could not get data: ' . mysql_error());
   }
$row = mysql_fetch_array($retval);
     echo"<br>ID :{$row[0]}  <br> ".
         "<br>IP: {$row[1]} <br> ".
         "<br>PORT : {$row[2]} <br> ".
         "<br>COMMUNITY : {$row[3]} <br> ".
         "<br>No. of SENT REQUESTS: {$row[4]} <br> ".
         "<br>No. of LOST REQUESTS : {$row[5]} <br> ".
         "<br>LAST UPDATED UPTIME: {$row[6]} <br> ".
         "<br>The WEB SERVER TIME : " . date("Y-m-d h:i:sa");
"--------------------------------<br>";
 ?>
</div>
</body>
</html>
