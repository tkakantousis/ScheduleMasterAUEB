<?php
require_once("include/global.php");
init(true);
$id = 0 + $_GET['id'];

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
WriteLog("Αφαιρέθηκε το μάθημα " . $row['lesson'] . " του τμήματος " . 
			$row['depart'] . " από το πρόγραμμα " . $row['schedu'] . " από την αίθουσα " . 
			$row['room'] . " και slot " . $row['dayn'] . " " . $row['timen']);
			
$db->query("DELETE FROM Submit WHERE id =" . $id . "");

?>
