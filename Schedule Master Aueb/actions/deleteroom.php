<?php
require_once '../include/global.php';
init(true);
$id = 0 + $_POST['id'];
$action = "" + $_POST['action'];

if ($action == "delete") 
{
	$db->query("DELETE FROM Room WHERE id=" . $id);
	$db->query("DELETE FROM Submit WHERE room_id=" . $db->sqlEsc($id) . "");
}				

header("Refresh: 0; url=" . $_POST['ref'] . "");
?>
