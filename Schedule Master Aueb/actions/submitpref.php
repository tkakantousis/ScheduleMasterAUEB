<?php
require_once("../include/global.php");
init(true);

$prohibs = $_GET['prohibs'];
$lesson = 0 + $_GET['lesson'];
/*if(!is_array($prohibs))
{
	echo "Error";
	die();
}*/
if(!is_array($prohibs))
{
	$db->query("DELETE FROM Preference WHERE lesson_id=".$lesson);
}
else
{
	$values = array();
	foreach ($prohibs as $value)
	{
		$values[] = "(" . $db->sqlEsc($lesson) . "," . $db->sqlEsc($value) . ",1)";
	}
	$db->query("DELETE FROM Preference WHERE lesson_id=".$lesson);
	$db->query("INSERT INTO Preference(lesson_id,slot_id,status) VALUES " . implode(",",$values));
	//header("Location: " . $_SERVER['HTTP_REFERER']. "");
}//else
header("Refresh: 0; url=" . $_POST['ref'] . "");
?>
