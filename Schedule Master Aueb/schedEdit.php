<?php
require_once("include/global.php");
init(true);

stdHeader("Edit Schedule");
$_ENV['Ayearcolor'] = $Ayearcol;
$_ENV['Byearcolor'] = $Byearcol;
$_ENV['Cyearcolor'] = $Cyearcol;
$_ENV['Dyearcolor'] = $Dyearcol;

$semester= 0 + $_GET['semester'];
$department= 0 + $_GET['dep_id'];
$schedule= 0 + $_GET['schedule'];
?>

<script type="text/javascript">
var slots = new Array();
var rooms = new Array();
var bgcolor1 = "<? echo ($_ENV['Ayearcolor']);?>";
var bgcolor2 = "<? echo ($_ENV['Byearcolor']);?>";
var bgcolor3 = "<? echo ($_ENV['Cyearcolor']);?>";
var bgcolor4 = "<? echo ($_ENV['Dyearcolor']);?>";
<?php
$where = getWhereClause(false);
if(strlen($where)>2)
	$where= " WHERE " . $where;


$res  = $db->query("	SELECT
					id,
					title
				FROM 
					Room
			");
while($row=mysql_fetch_array($res))
{
	?>
	rooms['<?=$row['id'] ?>'] = "<?=$row['title']?>";
	<?php
}



$res  = $db->query("	SELECT
							Lesson.id,
							Lesson.n_Slot,
							Lesson.title,
							if(counter IS NOT NULL,counter,0) AS counter,
							semester,
							department_id
						FROM
							(
								SELECT
									lesson_id,
									COUNT(lesson_id) as counter
								FROM
									Submit
								WHERE
									schedule_id=" . $schedule . "
								GROUP BY lesson_id
							) a
						RIGHT JOIN Lesson ON a.lesson_id = Lesson.id" . $where . "
					");
while($row=mysql_fetch_array($res))
{
	?>
	slots['<?=$row['id'] ?>'] = new Array(4);
	slots['<?=$row['id'] ?>'][1] = <?=$row['counter']?>;
	slots['<?=$row['id'] ?>'][2] = <?=$row['n_Slot']?>;
	slots['<?=$row['id'] ?>'][3] = <?=$row['semester']?>;
	slots['<?=$row['id'] ?>'][4] = <?=$row['department_id']?>;
	slots['<?=$row['id'] ?>'][5] = "<?=$row['title']?>";
	<?php
}
?>
var prohibs = new Array();
<?php
$where = getWhereClause(false);
if(strlen($where)>2)
	$where= " AND " . $where;
$res  = $db->query("	SELECT
							DISTINCT
							lesson_id
						FROM
							Preference
						INNER JOIN Lesson ON Lesson.id=Preference.lesson_id
						WHERE
							status=1" . $where . "
					");
while($row=mysql_fetch_array($res))
{
	?>
	prohibs['<?=$row['lesson_id']?>'] = new Array();
	<?php
}
$res  = $db->query("	SELECT
							lesson_id,
							slot_id
						FROM
							Preference
						INNER JOIN Lesson ON Lesson.id=Preference.lesson_id
						WHERE
							status=1" . $where . "
					");
while($row=mysql_fetch_array($res))
{
?>
	prohibs['<?=$row['lesson_id'] ?>'][<?=$row['slot_id']?>] = 1;
	<?php
}
?>

</script>

<table class="content">
	<tr>
		<th colspan="2">
		<div  class="formdiv2"><form method="get" action="schedEdit.php" name="ScheEdit">
			<? printForm("ScheEdit"); ?>
		</form>
        </div>
		</th>
	</tr>
	<tr>
		<td valign="top">
			<?php
			if($schedule>0)
				displayLessons($semester, $department);
			?>
		</td>
		<td>
			<?php
			if($schedule>0)
				printSchedule($schedule,$department,$semester);
			?>
		</td>
	</tr>
</table>
<div id="overlay"></div>
<?php
stdFooter();




















function printSchedule($schedule,$department,$semester)
{
	global $db;
	$where = getWhereClause();
	if(strlen($where)>2)
		$where = " WHERE " . $where;
	$res = $db->query("SELECT
						Lesson.id AS lesid,
						Submit.id,
						Lesson.semester,
						Lesson.department_id,
						Lesson.title AS lesTitle,
						rom.title AS romTitle,
						slot_id,
                                                comments
					FROM
						Submit
					INNER JOIN Room rom ON rom.id=Submit.room_id
					INNER JOIN Lesson ON Lesson.id=lesson_id" . $where);
	$cells=array();
	?>
	<script type="text/javascript">
		var inserted =new Array();
	<?php
	while($row = mysql_fetch_array($res))
	{
		if(!is_array($cells[$row['id']]))
			$cells[$row['id']] = array();
		$entry=array();
		$entry['id']= $row['id'];
		$entry['lesson']= $row['lesTitle'];
		$entry['lesid']= $row['lesid'];
		$entry['room']= $row['romTitle'];
		$entry['department_id']= $row['department_id'];
		$entry['semester']= $row['semester'];
                $entry['comments']= $row['comments'];

		$cells[$row['slot_id']][]= $entry;
		?>




		if(!isArray(inserted[<?=$row['slot_id']?>]))
			inserted[<?=$row['slot_id']?>] = new Array();
		var curslot = inserted[<?=$row['slot_id']?>];
		if(!isArray(curslot[<?=$row['department_id']?>]))
			curslot[<?=$row['department_id']?>] = new Array();
		var curdepart = curslot[<?=$row['department_id']?>];
		curdepart[<?=$row['semester']?>]=true;









<?php
	}
	echo "</script>";
	printGrid("printScheCell",$cells);
	$current = mysql_num_rows($res);
	$where = getWhereClause(false);
	if(strlen($where)>2)
		$where = " WHERE " . $where;
	$res = $db->query("SELECT SUM(n_Slot) as total FROM Lesson " . $where);
	$row = mysql_fetch_array($res);
	$total = $row['total'];
	?>
	<script type="text/javascript">
		var totalSlots = <?=$total ?>;
		var currentSlots = <?=$current ?>;
	</script>
	<?php
	echo "<div class=\"completedslots\"><a id=\"restSlots\" href=\"restoflessons.php?schedule=" . $_GET['schedule'] . "&dep_id=" . $_GET['dep_id'] . "&semester=" . $_GET['semester'] . "&semestertype=" . $_GET['semestertype'] . "\">Συμπληρωμένα slots:" . $current . "/" . $total . "</a></div>";
	//Print log
	$logres = $db->query("SELECT * FROM Log WHERE DATEDIFF(now(),created)<30 ORDER BY id DESC");
	echo "<br /><br /><div class=\"logscroll\"><table class=\"log\">";
	while($logrow = mysql_fetch_array($logres))
	{
		print("<tr><td class=\"time\">" . dbtime_to_user($logrow['created']) . "</td><td class=\"info\">" . $logrow['msg'] . "</td></tr>")	;
	}
	echo "</table></div>";
}










function printScheCell($slot_id,$cell)
{
	global $schedule;

	echo "<td class=\"scheduletd\" id=\"slo" . $slot_id . "\"  onmousedown=\"slomousedown(this)\" onmouseup=\"slomouseup(this)\">";
	if(is_array($cell))
	{
		$semesters = array();
		echo "<table class=\"innerSchedule\">";
		foreach($cell AS $entry)
		{
			$semester=$entry['semester'];
			if($semester==1||$semester==2)
				$semestercolor= $_ENV['Ayearcolor'];
			if($semester==3||$semester==4)
				$semestercolor= $_ENV['Byearcolor'];
			if($semester==5||$semester==6)
				$semestercolor= $_ENV['Cyearcolor'];
			if($semester==7||$semester==8)
				$semestercolor= $_ENV['Dyearcolor'];


			echo "<tr id=\"tr" . $entry['id'] . "\"><td class=\"innertd\" bgcolor=\"" . $semestercolor . "\">";
            echo "<div><img src=\"styles/images/pin.png\"></div>";
			print("" . $entry['lesson'] . "<br /> " . $entry['room'] . "<br />");
                        print("<div class=\"comments\">" . $entry['comments'] ."");
                      
                        print("<a href=\"#a\" onclick=\"confirmation(" . $entry['id'] . "," . $slot_id . "," . $schedule . "," . $entry['lesid'] . ")\">X</a>");


            echo "</td></tr>";
		}
		echo "</table>";
	}
	?>

	<form action="actions/submit.php" method="post" name="form<?=$slot_id ?>"  onsubmit="return dosubmit(this)">
	<input type = "hidden" name="schedule" value="<?=$schedule ?>" />
	<input type = "hidden" name="slot" value="<?=$slot_id ?>" />
	<input type = "hidden" name="lesson"/>

 		<div id="lightbox<?=$slot_id ?>" class="lightbox" style="display: none;">
		Επιλογή αίθουσας:<br />
                <input type="text" name="comments" value="">
		<input type="submit" value="Add"><input type="reset" value="Cancel" onclick="cancel()" />
	</div>
	</form>
<?php
	echo "</td>";
}


function displayLessons($semester, $department)
{
   global $db;
   $where = getWhereClause(false);
   	if(strlen($where)>2)
	$where = " WHERE " . $where;
   ?>
    <ul class="menu" id="menu2">
        <li><a href="#" class="menulink">Μαθήματα</a>

                <?php
                    $res = $db->query("SELECT * FROM Lesson " . $where . "" );
                    echo "<ul>";
                    while($row=mysql_fetch_array($res))
                    {
                        echo "<li>";
                        echo "<div onmousedown=\"lesmousedown(this)\" onmouseup=\"lesmouseup(this)\" id=\"les" . $row['id'] . "\" class=\"sub\">" . $row['title'] . "</div>";
                        echo "<ul>";
                        echo "<li class=\"topline\"><div href=\"#\">Καθηγητής: " . $row['professor']. "</div></li>";
                        echo "<li><div>Αριθμός Φοιτητών :". $row['size']. "</div></li>";

                        $res2 = $db->query("SELECT Count(lesson_id) FROM `Submit` WHERE lesson_id=".$row['id']." AND schedule_id=" . (0+$_GET['schedule']) . ";");
                        $row2 = mysql_fetch_array($res2);
                        echo "<li><div>Αριθμός Slot: ".$row2['Count(lesson_id)'] . "/". $row['n_Slot'] ."</div></li>";
                        echo "<li><div>Εξάμηνο: ".$row['semester']."</div></li>";
                        echo "<li><a href=\"lessonForm.php?id=". $row['id']. "\">Edit</a></li>";
                        echo "<li><a href=\"submitprefprof.php?lesson=". $row['id']. "\">Προτιμήσεις</a></li>";
                        echo "</ul>";
                        echo "</li>";
                    }
                    echo "</ul>";
                ?>

        </li>
    </ul>
	<script type="text/javascript">
        var menu2=new menu2.dd("menu2");
        menu2.init("menu2","menuhover");
    </script>
<?php
}

?>