<?php
require_once("../include/global.php");
header("Content-Type: text/html; charset=UTF-8");
init(true);

$lesson = 0 + $_GET['lesson'];
$schedule = 0 + $_GET['schedule'];
$slot = 0 + $_GET['slot'];

if(!($lesson>0 && $schedule>0 && $slot>0))
{
	echo "error";
	die();
}
$res = $db->query("SELECT size,semester,department_id FROM Lesson WHERE id=" . $lesson."");
$row = mysql_fetch_array($res);
$size = $row['size'];
$department = $row['department_id'];
$mod = $row['semester']%2;

$res = $db->query("
			SELECT 
				Room.id,
				Room.title

			FROM 
				Room
			WHERE department_id = ".$department." AND id NOT IN
					(
						SELECT 
							room_id
						FROM
							Submit
						INNER JOIN Lesson ON Lesson.id=Submit.lesson_id
						WHERE
							slot_id=" . $slot . "
							AND
							schedule_id=" . $schedule . "
							AND
							semester%2=" . $mod . "
					)
					AND
					size>" . $size . "
					
					");

$res2 = $db->query("
			SELECT
				Room.id,
				Room.title

			FROM
				Room
			WHERE department_id <> ".$department." AND id NOT IN
					(
						SELECT
							room_id
						FROM
							Submit
						INNER JOIN Lesson ON Lesson.id=Submit.lesson_id
						WHERE
							slot_id=" . $slot . "
							AND
							schedule_id=" . $schedule . "
							AND
							semester%2=" . $mod . "
					)
					AND
					size>" . $size . "

					");
while($row = mysql_fetch_array($res))
{
	echo $row['id'] . ":" . $row['title'] . ",";
}

while($row = mysql_fetch_array($res2))
{
	echo $row['id'] . ":" . $row['title'] . ",";
}
?>