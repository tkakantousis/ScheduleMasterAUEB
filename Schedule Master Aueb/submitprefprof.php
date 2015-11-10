<?php
require_once("include/global.php");
init(true);

$lesson = 0 + $_GET['lesson'];
if($lesson<=0)
	die();

stdHeader("Lesson Preference");
$res = $db->query("SELECT title FROM Lesson where id=".$lesson);
$row = mysql_fetch_array($res);

echo "Βάλε Προτιμήσεις για το μάθημα: ".$row['title'];
?>

<form action="actions/submitpref.php" method="get">
	<input type="hidden" name="lesson" value="<?=$lesson ?>" />
	<?php
		$res = $db->query("SELECT slot_id FROM Preference WHERE lesson_id=" . $lesson);
		$prohibSlots = array();

		while($row = mysql_fetch_array($res))
		{
			$prohibSlots[$row ['slot_id']] = 1;
		}

		printGrid("printLessonPrefCell",$prohibSlots);
	?>
	<INPUT TYPE="submit" value="Καταχώρηση" />
	 <input type="hidden" name="ref" value="<?=$_SERVER['HTTP_REFERER']?>" />
</form>
<?php 
stdFooter();

function printLessonPrefCell($slotId,$cellInfo)
{
	echo "<td class=\"scheduletd\" bgcolor=\"#FFFFFF\" align=\"center\" valign=\"middle\">";
	echo "<INPUT TYPE=CHECKBOX NAME=\"prohibs[]\" value=\"" . $slotId . "\"" . ($cellInfo==1?" checked=\"checked\"":"") . "/>";
	echo "</td>";
}

?>