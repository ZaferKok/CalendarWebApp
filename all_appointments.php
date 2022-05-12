<h1>You can search your appointments</h1>

<?php
$connect 				= mysqli_connect("localhost","root","","calendar");
						  mysqli_query($connect, "SET names utf8");					  
$querySearch			= "select * from appointment where user_nr_fk = ".$_SESSION["userId"];

$appointment_table 		= "<table class='tableAppointment'>";

echo "<form action='' method='post' class='search'>
	  <input type='text' name='search' placeholder='type here!'/>
	  <input type='submit' name='searchButton' value='SEARCH'/>
	  </form><br />";
	  
if(isset($_POST["search"]) && isset($_POST["searchButton"]))
{
	$_POST["search"] 	= mysqli_real_escape_string($connect, $_POST["search"]); // FOR SECURITY
	$querySearch 		= "select * from appointment where topic LIKE '%".$_POST["search"]."%' and user_nr_fk = ".$_SESSION["userId"];					  
	echo "<br /><h4 style='color:red;'>RESULTS of : ".$_POST["search"]."</h4>";
}

$all_appointments		= mysqli_query($connect, $querySearch);
	
echo build_appointments_table($appointment_table, $all_appointments);

function build_appointments_table($appointment_table, $all_appointments)
{
	$appointment_table .= 
	"<thead>
		<tr>
			<th class='tableTh'>No</th>
			<th class='tableTh'>Topic</th>
			<th class='tableTh'>Date</th>
			<th class='tableTh'>Id No</th>
		<tr/>
	</thead>
	<tbody>";
			
	$no = 0;
	while($info_line_appointment = mysqli_fetch_array($all_appointments))
	{
			$no++;
			$appointment_table .= "<tr><td class='tableTd'>".$no."</td>";
			$appointment_table .=     "<td class='tableTd'>".$info_line_appointment["topic"]."</td>";
			$appointment_table .=     "<td class='tableTd'>".$info_line_appointment["date"]."</td>";
			$appointment_table .=     "<td class='tableTd'>".$info_line_appointment["number"]."</td></tr>";	
	}
	$appointment_table .= "
	
	</tbody>
</table>";

	return $appointment_table;
}
?>