<?php
require_once("include/global.php");
init(false);
stdHeader("Εμφάνιση Κενών Αιθουσών ανά Πρόγραμμα");

$_ENV['available'] = $availableroomcol;
$_ENV['unavailable'] = $unavailableroomcol;
$schedule = 0 + $_GET['id'];



$cells = array();
$res = $db->query("
					SELECT 
						Slot.id AS slotid,
						Room.id AS roomid,
						Room.title,
						IF(Submit.lesson_id>0, '0', '1') AS available
					FROM Slot
					INNER JOIN Room
					LEFT JOIN Submit ON (Room.id=Submit.room_id AND Submit.slot_id=Slot.id AND schedule_id=" . $schedule . ") 
                    ");

while($row = mysql_fetch_array($res))
{
	$slot=$row[slotid];
	$cells[$slot][$row['roomid']]['name']=$row['title'];
	$cells[$slot][$row['roomid']]['available']=$row['available'];
}



printGrid("PrintAvailableRoomsOfSlot",$cells);

function PrintAvailableRoomsOfSlot($slot,$cell)
{
	echo "<td class=\"scheduletd\">";
	echo "<table width=\"100%\" class=\"innerSchedule\">";
	for($i=0;$i<sizeof($cell);$i++)
	{
		if(!is_array($cell[$i]))
			continue;
		$room=$cell[$i];
		if($room['available']==1)
		{
			echo "<tr><td class=\"innertd\" bgcolor=\"".$_ENV['available']."\" align=\"center\" valign=\"middle\">";
			echo "<b>" . $room['name'] . "</b>";
			echo "</td></tr>";
		}
		else
		{
			echo "<tr><td class=\"innertd\" bgcolor=\"".$_ENV['unavailable']."\" align=\"center\" valign=\"middle\">";
			echo "<b>" . $room['name'] . "</b>";
			echo "</td></tr>";
		}
	}
	echo "</table>";
	echo "</td>";
}
print("<br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /> ");	
stdFooter();