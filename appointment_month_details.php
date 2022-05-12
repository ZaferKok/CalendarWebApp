<h1>Appointment Month</h1>

<?php
$connect 	= mysqli_connect("localhost","root","","calendar");
$all_data	= "select 
			   appointment.topic,
			   appointment.date
			   
			   from appointment";

$variable 	= mysqli_query($connect, $all_data);

#var_dump($variable);

while($info_line = mysqli_fetch_array($variable))
{
	echo $info_line["topic"]; 	echo 	"<br />";
	echo $info_line["date"]; 	echo	"<br />";
	$savedAppointments[] = $info_line["date"];
}

print_r($savedAppointments);


	$month 	= getdate()["mon"];
	$year 	= getdate()["year"];


	$calendar 	= "<table class='calendarOtherMonths'><caption>";
	echo build_calendar($month, $year, $calendar, $savedAppointments);

function build_calendar($month, $year, $calendar, $savedAppointments) 
{
	$daysOfWeek 			= array("Mon","Tue","Wed","Thr","Fri","Sat","Sun");
	$firstDayOfMonth 		= mktime(0,0,0,$month,1,$year);
	$numberOfDaysInMonth 	= date("t", $firstDayOfMonth);
	$dateComponents			= getdate($firstDayOfMonth);
	$monthName				= $dateComponents["month"];
	$dayOfWeek				= ($dateComponents["wday"]+6)%7;
	echo "<br/>";

	////////////////////////// Empty appointment list ////////////////////////////////
	$AppointmentsOfThisMonth[] = null;
	for($i=0; $i<=12; $i++)
	{
		$AppointmentsOfThisMonth[$i] = 
		array(0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0);			
	}
	//////////////////////////////////////////////////////////////////////////////////	

	////////////////////////// Filled appointment list ////////////////////////////////	
	foreach($savedAppointments as $appointment)
	{
		for($i=0; $i<=12; $i++)
		{
			$AppointmentsOfThisMonth[(int)substr($appointment, -5, -3)]
			[(int)substr($appointment, -2)] = substr($appointment, -2);			
		}
	}
	//////////////////////////////////////////////////////////////////////////////////
	

	//////////////////////// CREATING SMALL TABLES IN YEAR ///////////////////////////
	$calendar  .= "<caption>
	<h4 style='display: flex;'><label><a href='?seite=appointment_month'>$monthName $year</a></label></h4>
				  </caption>";

	
	
	///////////////////////////////// DAY TITLES /////////////////////////////////////
	$calendar .= "<thead><tr>";
		foreach($daysOfWeek as $dayName)
		{
			$calendar .= "<th>$dayName</th>";
		}
	$calendar .= "</tr></thead>";
	//////////////////////////////////////////////////////////////////////////////////	
	
	
	///////////////////////////// DAY NUMBERS IN MONTH ///////////////////////////////
	$calendar .= "<tbody><tr>";
	if($dayOfWeek > 0) {$calendar .= "<td colspan=$dayOfWeek></td>";}
	
		$currentDay = 1;
	
		while($currentDay <= $numberOfDaysInMonth)
		{	
			if(($currentDay == getdate()["mday"] && $month == getdate()["mon"]))
			{
				$calendar .= "<td class='today'>$currentDay</td>";
				
			}
			else if($currentDay == $AppointmentsOfThisMonth[$month][$currentDay])
			{
				$calendar .= "<td class='appointmentDay'>$currentDay</td>";
			}
			else 
			{
				$calendar .= "<td class='day'>$currentDay</td>";
			}
			
			if(($dayOfWeek+$currentDay)%7 == 0)
			{
				$calendar .= "</tr><tr>";
			}
			$currentDay++;
		}
	$calendar .= "</tr>";
	//////////////////////////////////////////////////////////////////////////////////
	
	$calendar .= "</tbody>";
	$calendar .= "</table>";
	//////////////////////////////////////////////////////////////////////////////////

	return $calendar;
}
mysqli_close($connect);

?>