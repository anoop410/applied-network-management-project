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
<center>
    <h1>Assignment2</h1>
</center>    
<nav>
        <ul id="navlist">
            <li><a href="index.php">Add a Device</a></li>
            <li><a href="delete.php">Delete Device</a></li>
		 <li><a href="monitorboth.php">Details</a></li>
        </ul>
<br><br>
    </nav>
<br><br>


</head>
<body>
<script type="text/javascript">
checked=false;
function checkedAll (form1) {var aa= document.getElementById('form1'); if (checked == false)
{
checked = true
}
else
{
checked = false
}for (var i =0; i < aa.elements.length; i++){ aa.elements[i].checked = checked;}
}
</script>

<?php

require "db.php";


 // connection to database
 $database = mysql_connect("$host:$port",$userid,$passwd)
 or die ("Unable to connect to the Database");
 
 $connect = mysql_select_db("$name",$database)
 or die ("Database could not be selected");

//echo "Connected successfully";



$q1 = mysql_query("SELECT *FROM assign2_system");

#echo "<div id=\"section\">";
echo "<div style=\"width: 100%;\">";
echo "<div style=\"float:left; width: 50%\">";
echo "<h2>devices</h2>";
echo "<form id ='form1' action='getting.php' method='post'>";
    // output data of each row
    while($row = mysql_fetch_array($q1)) 
    {
        //echo "ip: " . $row["IP"]. " port: " . $row["PORT"]. " community" . $row["COMMUNITY"]."interfaces".$row["interfaces"]."\n";
       	$id=$row[0];
        $ip=$row[1];
        $port=$row[2];
        $community=$row[3];
				$interface=$row[4];
				$pieces = explode("&", $interface);
				$grapharray = array("hourly","daily", "monthly", "yearly");
				$graphvalue= array("-1h","-1d","-1m","-1y");
echo "$id";
echo "<input type='checkbox' name='check_list1[]' value=device$id> $ip<br>";


echo "<p style=\"text-indent: 5em\">INTERFACES</p>";
				foreach($pieces as $i => $value)
									{
									$z=$pieces[$i];
											echo "<p style=\"text-indent: 5em\"><input type='checkbox' name='device$id-check_list2[]'  value=device+$id+$ip+$port+$community+$z> $z<br></p>";
									
									}
								#	echo "<p style=\"text-indent: 5em\"><input type='checkbox' name='device$id-check_list2[]' onclick='checkedAll(form1);'>selectAll<br>";
									echo "<p style=\"text-indent: 5em\"><input type='checkbox' name='device$id-check_list2[]'  value=device+$id+$ip+$port+$community>all<br></p>";
									$l++;

}
echo "<p >Period for graph</p>";
foreach($grapharray as $f => $values)
									{
											$k=$grapharray[$f];
												$p=$graphvalue[$f];
											echo "<p style=\"text-indent: 5em\"><input type='checkbox' name='check_listtime[]'  value=$p> $k<br></p>";
									
									}
echo "<input type=\"submit\" value=\"device\">";
echo "</form>";
echo "</div>";
#echo "<div id=\"section\">";
echo "<div style=\"float:left;\">";
echo "<h2>servers</h2>";
$q1 = mysql_query("SELECT *FROM assign2servers");
   $detailsarray = array("cpuusage", "requestspersec", "transfbytespersec" , "bytesperrequest");
echo "<form action='gettingserver.php' method='post'>";
while($row = mysql_fetch_array($q1)) 
    {
    $grapharray = array("hourly","daily", "monthly", "yearly");
    $servername=$row[1];
    $ids=$row[0];
    echo "$ids";
    echo "<input type='checkbox' name='serverlist[]' value=$servername+$ids> $servername<br>";
    }
    
    echo "<p>Parameters</p>";
    /*foreach($detailsarray as $j => $values)
									{
											$h=$detailsarray[$j];
											echo "<p style=\"text-indent: 5em\"><input type='checkbox' name='server_parameter[]'  value=$h> $h<br></p>";
									
									}*/
									
echo "<p style=\"text-indent: 5em\"><input type='checkbox' name='server_parameter[]'  value=cpuusage> CPU Utilization<br></p>";
echo "<p style=\"text-indent: 5em\"><input type='checkbox' name='server_parameter[]'  value=requestspersec> Request Per second<br></p>";
echo "<p style=\"text-indent: 5em\"><input type='checkbox' name='server_parameter[]'  value=transfbytespersec> Transfered bytes per second<br></p>";
echo "<p style=\"text-indent: 5em\"><input type='checkbox' name='server_parameter[]'  value=bytesperrequest> Bytes per request<br></p>";
    
    echo "<p >Period for graph</p>";
 foreach($grapharray as $f => $values)
									{
											$k=$grapharray[$f];
											$p=$graphvalue[$f];
											echo "<p style=\"text-indent: 5em\"><input type='checkbox' name='server_time[]'  value=$p> $k<br></p>";
									
									}
echo "<input type=\"submit\" value=\"server&device\">";
echo "</form>";
echo "</div>";

echo "</div>";
#echo "<input type=\"submit\" value=\"compare\">";
?>
</body>
</html>
