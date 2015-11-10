<?php
require_once '../include/global.php';
init(true);
$id = 0 + $_POST['id'];
$title = "" . $_POST['title'];
$comment = "" . $_POST['comment'];
$published = "" . $_POST['published'];
$action = "" . $_POST['action'];

if($action == "delete") 
{
    $id = $_POST["schedule"];
    $res = $db->query("SELECT * FROM Schedule WHERE id=" . $id . "");
    if(mysql_num_rows($res)==1)
	{
		$row = mysql_fetch_array($res);
		WriteLog("Το πρόγραμμα " . $row['title'] . " σβήστηκε.");
		$db->query("DELETE FROM Schedule WHERE id = " . $db->sqlEsc($id));
		$db->query("DELETE FROM Submit WHERE schedule_id = " . $db->sqlEsc($id));
	}
	
}
else 
{	
	if(isset($_POST['id'])&&$id>=1)
	{
		$oldres = $db->query("SELECT * FROM Schedule WHERE id=" . $db->sqlEsc($id));
		$oldrow = mysql_fetch_array($oldres);
		if(mysql_num_rows($oldres)==0)
		{
			stdError("Error", "Λάθος στοιχεία");
		}

		if($oldrow['published']!=$published)
		{

			if($published == "on"){
				$publishedDate = $db->sqlEsc(now_datetime());
				$pub = "1";
				}
			else{
				$publishedDate="NULL";
				$pub = "0";
				}
		}

		$db->query("UPDATE Schedule SET "
					 . "title="     . $db->sqlEsc($title)  . ","
					 . "comment=" . $db->sqlEsc($comment)  . ","
					 . "publishedDate=" . $publishedDate   . ","
					 . "modified=" . $db->sqlEsc(now_datetime()) . ","
					 . "published="  . $db->sqlEsc($pub)    .
			  " WHERE id = " . $db->sqlEsc($id));
				 
		WriteLog("Έγινε επεξεργασία στο πρόγραμμα " . $oldrow['title'] . ".");
	}
	else
	{
		$db->query("INSERT INTO Schedule(title,comment,published,created,publishedDate)
			    VALUES (" . $db->sqlEsc($title)  . ","
				      . $db->sqlEsc($comment)   . ","
				      . $db->sqlEsc($published)    . ","
				      . " NOW() "   . ","
				      . " if(published=1,NOW(),NULL) " .
				  ")" );
		WriteLog("Δημιουργήθηκε πρόγραμμα " . $title . ".");
		//If we have selected the new program to be a copy of a previous one.
		$previousid = 0 + $_POST['previous'];
		if($previousid>0)
		{
		    $id = mysql_insert_id();
			$db->query("INSERT INTO Submit (lesson_id,slot_id,room_id,schedule_id,added) (SELECT lesson_id,slot_id,room_id," . $id . ",NOW() FROM Submit WHERE schedule_id=" . $previousid . ")");
		}
	}

}
header("Refresh: 0; url=../schedEdit.php?schedule=" . $id);
?>
