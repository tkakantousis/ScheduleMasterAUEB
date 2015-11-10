<?php
require_once("include/global.php");
init(false);
stdHeader("Εμφάνιση Προγράμματος φοιτητή");


if($_SERVER['REQUEST_METHOD']!="POST")
{
$res = $db->query("SELECT 
						Lesson.id AS lessonid,
						Lesson.title AS lessontitle,
						Department.title AS department,
						Lesson.semester,
						Lesson.professor 
					FROM 
						Lesson 
					INNER JOIN Department ON Department.id=Lesson.department_id 
					ORDER BY 
						department_id ASC, 
						semester ASC,
						Lesson.title ASC
					");
?>
<form method="post" name="lessonProgram">
	<?php 
		getSchedules($_GET['schedule'],"lessonProgram");
	?>
	<table class="content" bgcolor="#000000">
	<tr>
		<td class="colhead" bgcolor="#FFFFFF"></td>
		<td class="colhead" bgcolor="#FFFFFF">Τμήμα</td>
		<td class="colhead" bgcolor="#FFFFFF">Εξάμηνο</td>
		<td class="colhead" bgcolor="#FFFFFF">Τίτλος</td>
		<td class="colhead" bgcolor="#FFFFFF">Καθηγητής</td>
<?php 
 $currentDep = 0;
while($row=mysql_fetch_array($res))
{
	 echo "<tr>";
		 echo   "<td bgcolor=\"#FFFFFF\">
     					<input type=\"checkbox\" name=\"lessons[]\" value=\"" . $row['lessonid'] ."\" />
     					</td>";

     		echo   "<td bgcolor=\"#FFFFFF\">" . $row['department'] . "</td>";

     		
      echo "<td bgcolor=\"#FFFFFF\">" . $row['semester'] . "</td>
      	<td bgcolor=\"#FFFFFF\">".$row['lessontitle']."</td>
      	<td bgcolor=\"#FFFFFF\">" . $row['professor'] . "</td>
      	</tr>";
	
}


?>	</table>
<input type="submit" value="go" />
</form>

<?php 
die();
}
$schedule = 0  + $_POST['schedule'];
$lessons = $_POST['lessons'];

	$res = $db->query("SELECT 
					Lesson.title AS lesson,
					Room.title AS room,
					Submit.slot_id 
				FROM Submit
				INNER JOIN Lesson ON Lesson.id=Submit.lesson_id
				INNER JOIN Room ON Room.id=Submit.room_id
				WHERE Lesson.id IN(" . implode(",",$lessons) . ")");
$cells=array();
while($row=mysql_fetch_array($res))
{
	$entry = array($row['lesson'],$row['room']);
	if(!is_array($cells[$row['slot_id']]))
		$cells[$row['slot_id']] = array();
	array_push($cells[$row['slot_id']],$entry) ;
}
printGrid("printStudentCell",$cells);



function printStudentCell($slotId,$cell)
{
	echo "<td class=\"scheduletd\"  bgcolor=\"#FFFFFF\"><table class=\"innerSchedule\" width=\"100%\">";
	if(is_array($cell))
	foreach($cell AS $entry)
		echo "<tr><td class=\"innertd\" width=\"100%\" align=\"center\" bgcolor=\"#FFFFFF\">" . $entry[0] . "<br/>" . $entry[1] . "</td></tr>";
	echo "</table></td>";
}

stdFooter();
?>





