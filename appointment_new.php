<h1>New Appointment</h1>

<form action='?seite=appointment_new' method='post' style='height:500px; width:600px; margin-left:0;'>
	<br /><br />
	<h4>Please enter TOPIC<h4>
	<input required type='text' name='topic' placeholder='Topic here!' style='width:300px;'/><br /><br />
	<h4>Please select DATE<h4>
	<input required type='date' name='date' value="" style='width:300px;'/><br /><br /><br /><br />
	<input type='submit' value='SAVE' style='width:300px;'/><br /><br />
</form>

<?php

	
	if(isset($_POST['topic']) && isset($_POST['date']))
	{
		$check = 0;
		
		if($_POST['topic'] != "")
		{
			$check++;
		}
		
		if($_POST['date'] != "")
		{
			$check++;
		}
		
		if($check == 2 )
		{
			$order = "insert into appointment (user_nr_fk, topic, date) 	
					 values 
					 ('".$_SESSION["userId"]."', '".$_POST['topic']."', '".$_POST['date']."')";
												
			$connect = mysqli_connect("localhost","root","","calendar");
			mysqli_query($connect, $order);
			mysqli_close($connect);
			
			$message = 'You can enter new appointment again!';
			echo "<br /><h1>Your appointment has been saved!</h1>";
			echo "<h4 style='color:red;'>$message</h4>";
		}
		else
		{
			echo "<br /><h1 style='background-color:red; color:yellow;'>The boxes cannot be blank!</h1>";
		}
	}
?>