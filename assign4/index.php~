
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
<div class="col-md-2"style = "padding: 0;">
<h2>uptime Table</h2>
<?php
include "db.php";
   
   $conn = mysql_connect($hostname, $username, $password, $port);
   
   if(! $conn )
   {
      die('Could not connect: ' . mysql_error());
   }
   
   $sql = 'SELECT id, IP, PORT, COMMUNITY, lost, uptime FROM uptime';
   mysql_select_db($database);
   $retval = mysql_query( $sql, $conn );
   
   if(! $retval )
   {
      die('Could not get data: ' . mysql_error());
   }
echo "<table class='table table-bordered'><thead><tr><th>ID<br></th><th>IP<br></th><th>PORT<br></th><th>COMMUNTIY<br></th><th>UPTIME<br></th>
</tr></thead>";
$status_colors = array(0 => '#FFFFFF', 1 => '#FF0000', 2 => '#F80000', 3 => '#F00000', 4 => '#E80000', 5 => '#E00000', 6 => '#D80000', 7 => '#D00000', 8 => '#C80000', 9 => '#C00000', 10 => '#B80000', 11 => '#B00000', 12 => '#A80000', 13 => '#A00000', 14 => '#980000', 15 => '#900000', 16 => '#880000', 17 => '#800000', 18 => '#780000', 19 => '#700000', 20 => '#680000', 21 => '#600000', 21 => '#580000', 22 => '#500000', 23 => '#480000', 24 => '#400000', 25 => '#380000', 26 => '#300000', 27 => '#280000', 28 => '#200000', 29 => '#180000', 30 => '#100000');
   while($row = mysql_fetch_array($retval, MYSQL_NUM))
   {
   $f=$row[4];
echo "<tr><td>".$row[0]."<br></td><td><a href='uptime.php?key=".$row[0]."'>".$row[1]."</a><br></td><td>".$row[2]."<br></td><td>".$row[3]."<br></td><td bgcolor='$status_colors[$f]'>".$row[5]."</td><br></tr>";}
   mysql_close($conn);
?> 
</div>
</body>
</html>
