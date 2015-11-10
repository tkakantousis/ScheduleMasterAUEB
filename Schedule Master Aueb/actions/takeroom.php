<?php

require_once '../include/global.php';
init(true);
$id = 0 + $_POST['id'];
$title = "" . $_POST['title'];
$size = 0 + $_POST['size'];
$dep_id = 0 + $_POST['dep_id'];
$action = "" . $_POST['action'];


if(isset($_POST['id'])&&$id>0 && $action !="delete")
{
    $res = $db->query("SELECT * FROM Room WHERE id=" . $id . "");
    if(mysql_num_rows($res)==0)
	{
		stdError("Error", "Το μάθημα δεν υπάρχει");
	}
	$db->query("UPDATE Room SET "
				 . "title="     . $db->sqlEsc($title)  . ","
				 . "size=" . $db->sqlEsc($size)   . ","
				 . "department_id="  . $db->sqlEsc($dep_id) .
			   " WHERE id=" . $id . "");
	WriteLog("Έγινε επεξεργασία στην αίθουσα " . $title . ".");

}
else if($action == "delete")
{
    
    $res = $db->query("SELECT * FROM Room WHERE id=" . $id . "");
    if(mysql_num_rows($res)==0)
	{
		stdError("Error", "Δεν υπάρχει το μάθημα");
	}
	$row = mysql_fetch_array($res);
	$db->query("DELETE FROM Room WHERE id = " . $db->sqlEsc($id));
	$db->query("DELETE FROM Submit WHERE room_id=" . $db->sqlEsc($id) . "");
	WriteLog("Διαγράφηκε η αίθουσα" . $row['title'] . ".");
}
else
{
    $res = $db->query("SELECT * FROM Room WHERE title=" . $db->sqlEsc($title) . "");
    if(mysql_num_rows($res)!=0)
	{
		stdError("Error", "Υπάρχει αίθουσα με τον ίδιο τίτλο");
	}
    $db->query("INSERT INTO Room(title,size,department_id)
            VALUES (" . $db->sqlEsc($title)  . ","
                  . $db->sqlEsc($size)   . ","
                  . $db->sqlEsc($dep_id)    . "

              )" );
	WriteLog("Προστέθηκε η αίθουσα" . $title . ".");
}

header("Refresh: 0; url=" . $_POST['ref'] . "");


?>