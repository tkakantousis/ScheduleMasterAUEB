<?php
require_once("include/global.php");
init(false);
stdHeader("Εμφάνιση Διαθέσιμων Slot ανά Αίθουσα");

$roomid = 0 + $_GET['id'];
$schedule = 0 + $_GET['schedule'];
$title = "" . $_GET['title'];

$cells=array();
$res = $db->query("SELECT 
						slot_id  
					FROM Submit 
					WHERE 
						room_id =".$roomid." 
						AND schedule_id=".$schedule."
					");
							
while($row = mysql_fetch_array($res))
{
	$cells[$row[slot_id]]=1;
}
echo "Η αίθουσα " .$title." είναι διαθέσιμη: ";


printGrid("PrintAvailableCellsOfRoom",$cells);

function PrintAvailableCellsOfRoom($slot,$cell)
{
	if(!$cell==1)
	{
		echo "<td bgcolor=\"#00FF00\" align=\"center\" valign=\"middle\">";
		echo "<b>Available</b>";
		echo "</td>";
	}
	else
	{
		echo "<td bgcolor=\"#FF0000\" align=\"center\" valign=\"middle\">";
		echo "<b>Not available</b>";
		echo "</td>";
	}
}
print("<br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /> ");	
stdFooter();

?>