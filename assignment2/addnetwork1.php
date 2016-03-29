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
 
        <center><br>
        <form action = "addnetwork2.php" method = "POST">   
        <?php
        $IP = $_POST['IP'];
        $PORT = $_POST['PORT'];
        $COMMUNITY = $_POST['COMMUNITY'];
        $in = snmpwalk ("$IP:$PORT", "$COMMUNITY", "1.3.6.1.2.1.2.2.1.1");
        
         echo "<table border ='1'>
               <tr><td> IP </td>
                   <td> PORT </td>
                   <td> COMMUNITY </td></tr>";
         
             
        echo "<tr><td>" . "$IP" . "</td><td>" . "$PORT" . "</td><td>" . "$COMMUNITY" . "</td></tr>";
         echo "</table>";
         echo "<br><br> Select the interfaces to be monitored";
         echo "<br><table border = '1'>"; 
        foreach($in as $x){
        $i = explode(" ",$x);
        echo "<tr><td><input type = 'checkbox' name ='interfaces[]' value = $i[1],$COMMUNITY,$IP,$PORT> $i[1]</td></tr>";
} 
         ?>
         </table>
         <input type = "submit" value = "ADD">
         </form>
        
        </table>
        </div>
        </body>
</html>        
