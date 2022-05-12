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
	$savedAppointments[] = $info_line["date"];
}

if(!isset($_GET["selectedMonth"]))
{
	$_GET["selectedMonth"] = 0;
}

	$month 	= getdate()["mon"];
	$year 	= getdate()["year"];
	
	 
	echo "<div class='title'><h1>Welcome to $year Calendar</h1></div>";
	echo "<div style='background-color:yellow; 	width:100px; height:25px; display:inline-block; color:blue; border: 1px solid blue;'>today</div>";

	
	echo "<div class='container'>
			<div class='row'>";
				for($month=1; $month<=12; $month++)
				{
					echo "<div class='col-3'>";
					if($month == getdate()["mon"])
					{
						$calendar 	= "<table class='calendarCurrent '><caption>";
						echo build_calendar($month, $year, $calendar, $savedAppointments);
					}
					else
					{
						$calendar 	= "<table class='calendarOtherMonths'><caption>";
						echo build_calendar($month, $year, $calendar, $savedAppointments);
					}
					echo "</div>";
				}
		echo "</div>
			</div><br />";
	

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
						<h4 style='display: flex;'>
							<label class='kleinTableTitle'>
								<a href='?seite=appointment_month&selectedDate=$month-$year'>$monthName $year
								</a>
							</label>
						</h4>
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
				$calendar .= "<td class='today' style='border:1px solid blue;'>$currentDay</td>";
				
			}
			/*else if($currentDay == $AppointmentsOfThisMonth[$month][$currentDay])
			{
				$calendar .= "<td class='appointmentDay'>$currentDay</td>";
			}*/
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

