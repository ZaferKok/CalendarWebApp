<?php
$connect 	= mysqli_connect("localhost","root","","calendar");
$all_data	= "select 
			   appointment.topic,
			   appointment.date,
			   appointment.number
			   
			   from appointment
			   
			   where user_nr_fk =".$_SESSION["userId"];

$variable 	= mysqli_query($connect, $all_data);

	$month 		= (int)substr($_GET["selectedDate"], -7, -5);
	$year 		= substr($_GET["selectedDate"], -4);
	$monthName 	= date('F', mktime(0, 0, 0, $month, 10));
	
	if(isset($_POST['next']))
	{	
		$month++;
		if($month<=12)
		{
			header("Location: ?seite=appointment_month&selectedDate=$month-$year");
			exit;
		}else if($month>12)
		{
			$month = $month%12;
			$year++;
			header("Location: ?seite=appointment_month&selectedDate=$month-$year");
			exit;
		}else
		{
			$year = 2024;
			header("Location: ?seite=appointment_month&selectedDate=$month-$year");
			exit;
		}
	}
	
	if(isset($_POST['prev']))
	{	
		$month--;
		if($month>0)
		{
			header("Location: ?seite=appointment_month&selectedDate=$month-$year");
			exit;
		}else if($month<=0)
		{
			$month = 12;
			$year--;
			header("Location: ?seite=appointment_month&selectedDate=$month-$year");
			exit;
		}else
		{
			$year=2021;
			header("Location: ?seite=appointment_month&selectedDate=$month-$year");
			exit;
		}
	}
	

	echo "<div class='title'><h1>Your Appointments in $monthName</h1></div>";

	$calendar 	= "<table class='selectedMonth'><caption>";
	echo build_calendar($month, $year, $calendar, $variable);

function build_calendar($month, $year, $calendar, $variable) 
{
	$daysOfWeek 			= array("Mon","Tue","Wed","Thr","Fri","Sat","Sun");
	$firstDayOfMonth 		= mktime(0,0,0,$month,1,$year);
	$numberOfDaysInMonth 	= date("t", $firstDayOfMonth);
	$dateComponents			= getdate($firstDayOfMonth);
	$monthName				= $dateComponents["month"];
	$dayOfWeek				= ($dateComponents["wday"]+6)%7;
	echo "<br/>";
	
	////////////////////////// Get Data From Database ////////////////////////////////
	while($info_line = mysqli_fetch_array($variable))
	{
		$savedAppointmentsDate[] = $info_line["date"];
		$savedAppointmentsTopic[(int)substr($info_line["date"],-2)][] = $info_line["topic"];
		$savedAppointmentsNumber[(int)substr($info_line["date"],-2)][] = $info_line["number"];
	}
	//////////////////////////////////////////////////////////////////////////////////
	
	
	////////////////////////// Empty appointment list ////////////////////////////////
	$AppointmentsOfThisMonth[] = null;
	for($i=0; $i<=12; $i++)
	{
		$AppointmentsOfThisMonth[$i] = 
		array(0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0);			
	}
	//////////////////////////////////////////////////////////////////////////////////	

	////////////////////////// Filled appointment list ////////////////////////////////	
	foreach($savedAppointmentsDate as $appointment)
	{
		for($i=0; $i<=12; $i++)
		{
			$AppointmentsOfThisMonth[(int)substr($appointment, -5, -3)]
			[(int)substr($appointment, -2)] = substr($appointment, -2);			
		}
	}
	//////////////////////////////////////////////////////////////////////////////////
	

	//////////////////////// CREATING SMALL TABLES IN YEAR ///////////////////////////
	$calendar  .=  "<caption style='display: flex;'>
						<form action ='' method='post'>
							<input type='submit' name='prev' value='previous month' style='border-radius:20px;'/>
						</form>
						<h4 >
							<label class='bigTableTitle'>$monthName $year
							</label>
						</h4>
						<form action ='' method='post'>
							<input type='submit' name='next' value='next month' style='border-radius:20px; padding-left:25px; padding-right:25px'/>
						</form>
				    </caption>
				    ";

	
	///////////////////////////////// DAY TITLES /////////////////////////////////////
	$calendar .= "<thead><tr>";
		foreach($daysOfWeek as $dayName)
		{
			$calendar .= "<th class='bigTh'>$dayName</th>";
		}
	$calendar .= "</tr></thead>";
	//////////////////////////////////////////////////////////////////////////////////	
	
	
	///////////////////////////// DAY NUMBERS IN MONTH ///////////////////////////////
	$calendar .= "<tbody><tr>";
	if($dayOfWeek > 0) {$calendar .= "<td colspan=$dayOfWeek></td>";}
	
		$currentDay = 1;
	#$savedAppointmentsTopic[$currentDay]
		while($currentDay <= $numberOfDaysInMonth)
		{	
			if(($currentDay == getdate()["mday"] && $month == getdate()["mon"] && $year == getdate()['year']))
			{
				$calendar .= "<td class='today bigTd'>$currentDay<br />";
				
				foreach($savedAppointmentsTopic[$currentDay] as $topic)
				{
					$index 		= array_search($topic, $savedAppointmentsTopic[$currentDay]);
					$nummer 	= $savedAppointmentsNumber[$currentDay][$index];
					$calendar  .= "<p class='topic'>$topic <a href='?seite=appointment_edit&appnmbr=$nummer'>&#9998</a>
								   <a href='?seite=appointment_delete&appnmbr=$nummer'>&#10005</a></p><br/>";
				}
				$calendar .= "</td>";
			}
			else if($currentDay == $AppointmentsOfThisMonth[$month][$currentDay] && $year == getdate()['year'])
			{
				
				if($month < getdate()['mon'] || ($month == getdate()['mon'] && $currentDay < getdate()['mday']))
				{
					$calendar .= "<td class='appointmentDay bigTd' style='background-color:gray; color:white;'>$currentDay<br />";
				}else 
				{
					$calendar .= "<td class='appointmentDay bigTd'>$currentDay<br />";
				}
				
				foreach($savedAppointmentsTopic[$currentDay] as $topic)
				{
					$index 		= array_search($topic, $savedAppointmentsTopic[$currentDay]);
					$nummer 	= $savedAppointmentsNumber[$currentDay][$index];
					$calendar  .= "<p class='topic'>$topic <a href='?seite=appointment_edit&appnmbr=$nummer'>&#9998</a>
								   <a href='?seite=appointment_delete&appnmbr=$nummer'>&#10005</a></p><br/>";
				}
				$calendar .= "</td>";
			}
			else 
			{
				$calendar .= "<td class='day bigTd'>$currentDay</td>";
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
	echo "<br/>";
	echo "<br/>";

?>