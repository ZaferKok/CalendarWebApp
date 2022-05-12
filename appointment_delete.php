<h1>Appointment Delete</h1>

<?php

if(isset($_GET['appnmbr']) && $_GET['appnmbr'] != "") {
	
	$number = $_GET['appnmbr'];
	
	$connect 	= mysqli_connect("localhost","root","","calendar");
	$all_data	= "select 
				   appointment.topic,
				   appointment.date
			   
				   from appointment
					
				   where $number = appointment.number";

	$variable 	= mysqli_query($connect, $all_data);
	$info_line 	= mysqli_fetch_array($variable);
}

$year 	= substr($info_line['date'],0,4);
$month 	= (int)substr($info_line['date'],5,2);

echo "<h1>Month: $month - Year: $year</h1>";

echo "<hr /><br />";
$topic 	= $info_line['topic'];
echo "<h2 style='color:blue'>TOPIC</h2><h2>$topic</h2><br />"; 
echo "<hr /><br />";
$date 	= $info_line['date'];
echo "<h2 style='color:blue'>DATE</h2><h2>$date</h2><br />"; 
echo "<hr />";
echo "<h2 class='warning'>Are you sure you want to delete the appointment?</h2>"; 
echo "<form action='' method='post' style='width:600px; height:60px; margin-left:0;'>
		<input name='appointment_delete_yes' type='submit' value='YES' style='width:100px; margin-right: 50px;'>
		<input name='appointment_delete_no' type='submit' value='NO' style='width:100px; margin-left: 50px;'>
	 </form>";

$year = substr($info_line['date'],0,4);
$month = (int)substr($info_line['date'],5,2);

if(isset($_POST['appointment_delete_yes']))
{
	$order = "delete from appointment where number = '$number'"; 
	
	$connect = mysqli_connect("localhost","root","","calendar");
	
	mysqli_query($connect, $order);
	
	mysqli_close($connect);
	
	header("Location: ?seite=appointment_month&selectedDate=$month-$year");
	exit;
}

if(isset($_POST['appointment_delete_no']))
{
	header("Location: ?seite=appointment_month&selectedDate=$month-$year");
	exit;
}
?>