<?php
//Called by ajax to submit a lesson on a schedule,slot,room 

require_once("../include/global.php");
init(true);
$id = 0 + $_GET['id'];
$lessonid = 0 + $_GET['lesson'];
$roomid = 0 + $_GET['room'];
$slotid = 0 + $_GET['slot'];
$scheduleid = 0 + $_GET['schedule'];
$comments = "" . $_GET['comments'];

$db->query("INSERT INTO Submit VALUES (NULL,".$lessonid.",".$roomid.",".$slotid.",".$scheduleid.", NOW()," . $db->sqlEsc($comments) . ")");
$id = mysql_insert_id();
//mysql_query('set character set greek');
//mysql_query("SET NAMES 'greek'");
$res = $db->query("SELECT 
						Lesson.title AS lesson,
						Department.title AS depart,
						Schedule.title AS schedu,
						Room.title AS room,
						Day.name as dayn,
						Time.time as timen 
					From 
						Submit
					INNER JOIN Schedule ON Submit.schedule_id = Schedule.id
					INNER JOIN Lesson ON Submit.lesson_id = Lesson.id
					INNER JOIN Slot ON Submit.slot_id = Slot.id
					INNER JOIN `Time` ON Slot.time_id = Time.id
					INNER JOIN `Day` ON Slot.day_id = Day.id					
					INNER JOIN Room ON Submit.room_id = Room.id
					INNER JOIN Department ON Lesson.department_id = Department.id
					WHERE Submit.id= " . $id . ""
				);
$row = mysql_fetch_array($res);
WriteLog("Προστέθηκε το μάθημα " . $row['lesson'] . " του τμήματος " . 
			$row['depart'] . " στο πρόγραμμα " . $row['schedu'] . " στην αίθουσα " . 
			$row['room'] . " στο slot " . $row['dayn'] . " " . $row['timen']);




//Print entry id so as to be able to delete it through ajax 
echo $id;
?>