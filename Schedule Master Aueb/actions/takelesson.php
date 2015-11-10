<?php
require_once '../include/global.php';
init(true);
$id = 0 + $_POST['id'];
$title = "" . $_POST['title'];
$prof = "" . $_POST['professor'];
$sem = 0 + $_POST['semester'];
$slots = 0 + $_POST['slots'];
$size = 0 + $_POST['size'];
$dep_id = 0 + $_POST['dep_id'];


$action = "" . $_POST["action"];

if($action == "delete") 
{
	$res = $db->query("SELECT * FROM Lesson WHERE id=" . $db->sqlEsc($id));
	if(mysql_num_rows($res)>0)
	{
		$row = mysql_fetch_array($res);
		$db->query("DELETE FROM Submit WHERE lesson_id=" . $db->sqlEsc($id) . "");
		$db->query("DELETE FROM Lesson WHERE id = " . $db->sqlEsc($id) . "");
		WriteLog("Διαγράφηκε το μάθημα " . $row['title']);
	}
}
else 
{
	// an oxi diagrafi! 
	if($title==""||$prof==""||$sem==0||$slots==0||$size==0||$dep_id==0)
		die("Wrong input");

	if(isset($_POST['id'])&&$id>0)
	{
		$db->query("UPDATE Lesson SET "
					 . "title="     . $db->sqlEsc($title)      . ","
					 . "professor=" . $db->sqlEsc($prof)   . ","
					 . "semester="  . $db->sqlEsc($sem)    . ","
					 . "n_Slot="    . $db->sqlEsc($slots)  . ","
					 . "size="      . $db->sqlEsc($size)   . ","
					 . "department_id="    . $db->sqlEsc($dep_id) .
			  " WHERE id = " . $db->sqlEsc($id));
		WriteLog("Έγινε επεξεργασία στο μάθημα " . $title);
	}
	else
	{
		$db->query("INSERT INTO Lesson(title,professor,semester,n_Slot,size,department_id)
			    VALUES (" . $db->sqlEsc($title)  . ","
				      . $db->sqlEsc($prof)   . ","
				      . $db->sqlEsc($sem)    . ","
				      . $db->sqlEsc($slots)  . ","
				      . $db->sqlEsc($size)   . ","
				      . $db->sqlEsc($dep_id) . "
				  )" );
		WriteLog("Προστέθηκε το μάθημα " . $title);
	}
}


header("Refresh: 0; url=" . $_POST['ref'] . "");
?>
