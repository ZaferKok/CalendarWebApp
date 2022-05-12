<h1>Appointment Edit</h1>

<?php

if(isset($_GET['appnmbr']) && $_GET['appnmbr'] != "")
{
	$number 	= $_GET['appnmbr'];
	$connect 	= mysqli_connect("localhost","root","","calendar");
	$order		= "select * from appointment where appointment.number = $number";
	$query		= mysqli_query($connect, $order);
	$info_line	= mysqli_fetch_array($query);
	
	$year 		= substr($info_line['date'],0,4);
	$month 		= (int)substr($info_line['date'],5,2);	
}

echo "<h1>Month: $month - Year: $year</h1>";

echo "<form action='' method='post' style='height:500px; width:600px; margin-left:0;'>
		<br /><br />
		<h4>Please edit TOPIC<h4>
		<input required type='text' name='topic' placeholder='".$info_line['topic']."' style='width:300px;' /><br /><br />
		<h4>Please select new DATE<h4>
		<input type='date' name='date' value='".$info_line['date']."' style='width:300px;'/><br /><br /><br /><br />
		<input type='submit' name='save' value='SAVE' style='width:300px;'/><br /><br />
	 </form>";

if(isset($_POST['save']))
{
	$userId		= $_SESSION["userId"];
	$newTopic	= $_POST['topic'];
	$newDate	= $_POST['date'];
	$newOrder	= "update appointment 
				   SET topic='$newTopic', date='$newDate', user_nr_fk='$userId' 
				   where '$number'=appointment.number";
				  
	mysqli_query($connect, $newOrder);
	
	
	
	
	#header("Location: ?seite=appointment_edit&appnmbr=$number");

	echo "<br /><h1 style='background-color:yellow;'>Your appointment has been updated!</h1>";
}
mysqli_close($connect);

?>