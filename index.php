<html>
	
	<head>
	<title>Calendar</title>
	<link rel="icon" href="images/icon.png"
	<meta charset="utf-8" />
	<link rel="stylesheet" href="css/app.css">
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" 
		  rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" 
		  crossorigin="anonymous">
	</head>
	
	
	<body>
		<header style="padding-bottom:15px; padding-top:25px;">
<?php
session_start();

#////////////////////// CONNECTION /////////////////////////
$connect 	= mysqli_connect("localhost","root","","calendar");
$all_data	= "select 
				
				user.user_nr,
				user.username,
				user.password	
				
				from user";	
				
$variable	= mysqli_query($connect, $all_data);
#var_dump($variable);
# ////////////////////// CONNECTION /////////////////////////


if(isset($_GET["seite"]) && ($_GET["seite"] == "logout"))
{
	unset($_SESSION["logged_in"]);
}

#////////////////////// LOGIN /////////////////////////
while($all_info	= mysqli_fetch_array($variable))
{
	#echo $all_info["username"];
	if(isset($_POST["user"]))
	{
		if($_POST["user"] 		== $all_info["username"] 
		&& $_POST["password"] 	== $all_info["password"])
		{
			$_SESSION["logged_in"] 	= true;
			$_SESSION["userId"] 	= $all_info["user_nr"];
			$_SESSION["username"]	= $all_info["username"];
			header("location: ?seite=annual_calendar");
			exit;
		}
	}
	
}
mysqli_close($connect);
#////////////////////// LOGIN /////////////////////////

if(isset($_SESSION["logged_in"]))
{
	echo "<a href='?seite=all_appointments'>ALL APPOINTMENTS</a>";
	echo "<a href='?seite=annual_calendar'>ANNUAL CALENDAR</a>";
	echo "<a href='?seite=appointment_new'>NEW APPOINTMENT</a>";
	echo "<a href='?seite=logout'>LOGOUT</a><br/><br/>";
	echo "<p style='color:orange; display:inline;'>user </p>
		  <p style='color:white; display:inline;'>".$_SESSION["username"]."</p>";
}
else
{
	echo "<a href='?seite=home'>HOME</a>";
	echo "<a href='?seite=login'>LOGIN</a>";	
}
?>		
		</header>
		
		
		
		
<!--////////////////////// MAIN /////////////////////////-->
		<main> 
<?php	
if(!isset($_GET["seite"]))
{
	$_GET["seite"] = "home";
}
switch($_GET["seite"])
{
	case "home" 			  : include("home.php"); 				break;
	case "login"			  : include("login.php"); 				break;
	case "logout"			  : include("logout.php"); 				break;
	case "info"				  : include("info.php"); 				break;
	case "contact"			  : include("contact.php"); 			break;
	case "annual_calendar"    : include("annual_calendar.php"); 	break;
	case "appointment_new"    : include("appointment_new.php"); 	break;
	case "appointment_month"  : include("appointment_month.php"); 	break;
	case "appointment_edit"   : include("appointment_edit.php");  	break;
	case "appointment_delete" : include("appointment_delete.php");  break;
	case "all_appointments"   : include("all_appointments.php");    break;

	default 				  : echo "<h1>404</h1>";
}		
?>		
		</main> 
<!--////////////////////// MAIN /////////////////////////-->
	
	
	
		<footer>
<?php
echo "<img src='images/icon.png' alt='calendar icon' width='20' height='20'>";
echo "<p>Calendar Zafer 2022</p>";
echo "<a href='?seite=info'>Info</a><br />";
echo "<a href='?seite=contact'>Contact</a>";	
?>
		</footer>
	</body>
	

</html>


