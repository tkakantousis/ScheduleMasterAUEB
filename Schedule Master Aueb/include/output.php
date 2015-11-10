<?php
function stdHeader($title)
{
    global $db;
	header("Content-Type: text/html; charset=utf-8");
	?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
   "http://www.w3.org/TR/html4/loose.dtd">

	<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
	<head>
	<title><?=$title?></title>
        <script type="text/javascript" src="resources/menu.js"></script>
   	<script type="text/javascript" src="resources/menu2.js"></script>
	<script type="text/javascript" src="resources/validate.js"></script>
        <script type="text/javascript" src="resources/deleteSub.js"></script>
        <script type="text/javascript" src="resources/dragNdrop.js"></script>

    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>

        <link rel="icon"  href="/vhosts/favicon.ico" />

    <link rel="stylesheet" href="styles/default.css" type="text/css" />
	</head>
	<body>
	    
        <ul class="menu" id="menu">
            <li><a href="#" class="menulink">Μενού Αίθουσας</a>
                <ul>
                    <li><a href="roomForm.php">Εισαγωγή/Διαγραφή Αίθουσας</a></li>
                    <li>
                        <a href="#" class="sub">Επεξεργασία Αίθουσας &nbsp; </a>
                        <ul>
                        <?php
                             $schedule = 0 + $_GET['schedule'];
                             $res = $db->query("SELECT * FROM Room" );
                             while($row=mysql_fetch_array($res))
                             {
                                 echo "<li class=\"topline\"><a href=\"roomForm.php?id=".$row['id']."\">".$row['title']."</a></li>";

                             }

                        ?>
                        </ul>
                    </li>
                    <li>
                        <a href="#" class="sub">Κενές Αίθουσες Προγράμματος &nbsp;</a>
                        <ul>
                        <?php
                             $res = $db->query("SELECT * FROM Schedule" );
                             while($row=mysql_fetch_array($res))
                             {
                                 echo "<li class=\"topline\"><a href=\"availableRoom_Slot.php?id=".$row['id']."\">".$row['title']."</a></li>";

                             }

                        ?>
                        </ul>
                    </li>
                 </ul>
            </li>
             <li><a href="#" class="menulink">Μενού Μαθήματος</a>
                <ul>
                    <li><a href="lessonForm.php">Εισαγωγή/Διαγραφή Μαθήματος</a></li>
                    <li><a href="#" class="sub">Επεξεργασία Μαθήματος &nbsp; </a>
                        <ul>
                        <?php

                             $res = $db->query("SELECT * FROM Department" );

                             while($row=mysql_fetch_array($res))
                             {

                                echo "<li><a href=\"#\" class=\"sub\">".$row['title']."&nbsp;</a>"; //Department
                                $did = $row['id'];

                                echo "<ul>";
                                for( $i=1; $i<=8;$i++)
                                {
                                    echo "<li><a href=\"#\" class=\"sub\">Εξάμηνο ".$i."</a>"; //Semester
                                    echo "<ul>";
                                    $res3 = $db->query("SELECT * FROM Lesson WHERE semester=".$i." AND department_id=".$row['id'].";");
                                    while($row3=mysql_fetch_array($res3))
                                    {
                                        echo "<li class=\"topline\"><a href=\"lessonForm.php?id=".$row3['id']."\">".$row3['title']."</a></li>"; //Lesson

                                    }
                                    echo "</ul>";
                                }
                                echo "</ul>";
                                echo "</li>";

                            }

                        ?>
                        </ul>
                    </li>
                  </ul>
            </li>
            <li><a href="#" class="menulink">Μενού Προγράμματος</a>
                <ul>
                    <li><a href="scheduleForm.php">Εισαγωγή/Διαγραφή Προγράμματος</a></li>
                    <li>
                        <a href="#" class="sub">Εμφάνιση Προγράμματος &nbsp;</a>
                        <ul>
                        <?php
                             $res = $db->query("SELECT * FROM Schedule" );
                             while($row=mysql_fetch_array($res))
                             {
                                 echo "<li class=\"\"><a href=\"schedEdit.php?schedule=".$row['id']."&dep_id=1&semester=1\">".$row['title']."</a></li>";

                             }

                        ?>
                        </ul>
                    </li>
                    <li>
                        <a href="#" class="sub">Επεξεργασία Προγράμματος &nbsp;</a>
                        <ul>
                        <?php
                             $res = $db->query("SELECT * FROM Schedule" );
                             while($row=mysql_fetch_array($res))
                             {
                                 echo "<li class=\"topline\"><a href=\"scheduleForm.php?id=".$row['id']."\">".$row['title']."</a></li>";

                             }

                        ?>
                        </ul>
                    </li>
                </ul>
            </li>
        </ul>
           <script type="text/javascript">
        	var menu=new menu.dd("menu");
        	menu.init("menu","menuhover");
    	</script>
    	<br /><br /><br />
	<?php
}

function stdHeader2($title)
{
    global $db;
	header("Content-Type: text/html; charset=utf-8");
	?>
	<!DOCTYPE html PUBLIC "-//W3C//XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
	<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
	<head>
	<title><?=$title?></title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>

    <link rel="icon" href="/vhosts/favicon.ico" />
    <link rel="stylesheet" href="styles/default.css" type="text/css" />
	</head>
	<body>
	<?php
}


function stdFooter()
{
	?>
            <br />
	<br />
	
	<br />
	<!--<div class= "footer">Το Schedule Master Aueb αναπτύχθηκε στα πλαίσια του μαθήματος "Ανάπτυξη Εφαρμογών Πληροφοριακών Συστημάτων"
	του τμήματος Πληροφορικής του Οικονομικού Πανεπιστημίου Αθηνών, το έτος 2009.&nbsp;&nbsp;<a href = "about.php" ><img src="styles/images/info8small.png" border="0" alt="info" /></img></a>
</div>-->
<script type="text/javascript">
var gaJsHost = (("https:" == document.location.protocol) ? "https://ssl." : "http://www.");
document.write(unescape("%3Cscript src='" + gaJsHost + "google-analytics.com/ga.js' type='text/javascript'%3E%3C/script%3E"));
</script>
<script type="text/javascript">
try {
var pageTracker = _gat._getTracker("UA-9859591-4");
pageTracker._trackPageview();
} catch(err) {}</script>
</body>
</html><?php
}

function stdError($header,$info)
{
	echo $header . "<br/>";
	echo $info;
	die();
}

function getSemesters($selected,$onchangeform="")
{
	if($onchangeform!="")
		$onchange = " onchange=\"document.forms['" . $onchangeform . "'].submit()\"";
	?>
	<select name="semester" <?=$onchange ?>>
		<option value="0" <?=($selected==0?" selected=\"selected\"" :"")?>>Όλα</option>
		<?php
		for ($i=1; $i<=8; $i++)
		{
			if ($i == $selected)
				print("<option value=\"". $i ."\" selected=\"selected\">" . $i . "ο</option>");
			else
				print("<option value=\"". $i ."\" >" . $i . "ο</option>");
		}
		?>
	</select><?php
}

function getRooms($selected,$onchangeform="")
{
	global $db;
	if($onchangeform!="")
		$onchange = " onchange=\"document.forms['" . $onchangeform . "'].submit()\"";
?>
	<select name="id" <?=$onchange ?>>
		<option value="0" <?=($selected==0?" selected=\"selected\"" :"")?>>Όλες</option>
		<?php
		$res2 = $db->query("SELECT title,id FROM Room ");
		while($row2 = mysql_fetch_array($res2))
		{

			if ($selected == $row2['id'])
				print("<option value=\"". $row2['id'] ."\" selected=\"selected\">" . $row2['title'] . "</option>");
			else
				print("<option value=\"". $row2['id'] ."\" >" . $row2['title'] . "</option>");
		}
		?>
	</select><?php
}

function getLesson($selected,$onchangeform="")
{
	global $db;
	if($onchangeform!="")
		$onchange = " onchange=\"document.forms['" . $onchangeform . "'].submit()\"";
?>
	<select name="id" <?=$onchange ?>>
		<option value="0" <?=($selected==0?" selected=\"selected\"" :"")?>>Όλα</option>
		<?php
		$res2 = $db->query("SELECT title,id FROM Lesson ");
		while($row2 = mysql_fetch_array($res2))
		{

			if ($selected == $row2['id'])
				print("<option value=\"". $row2['id'] ."\" selected=\"selected\">" . $row2['title'] . "</option>");
			else
				print("<option value=\"". $row2['id'] ."\" >" . $row2['title'] . "</option>");
		}
		?>
	</select><?php
}


function getDepartments($selected,$onchangeform="", $koino=false)
{
	global $db;
	if($onchangeform!="")
		$onchange = " onchange=\"document.forms['" . $onchangeform . "'].submit()\"";
		
?>
	<select name="dep_id" <?=$onchange ?>>
		<option value="0" <?=($selected==0?" selected=\"selected\"" :"")?>>Όλα</option>
		<?php
		if($koino)
		{
			$res2 = $db->query("SELECT title,id FROM Department");
		}
		else{	$res2 = $db->query("SELECT title,id FROM Department WHERE id<>9");}
		while($row2 = mysql_fetch_array($res2))
		{
			if ($selected == $row2['id'])
				print("<option value=\"". $row2['id'] ."\" selected=\"selected\">" . $row2['title'] . "</option>");
			else
				print("<option value=\"". $row2['id'] ."\" >" . $row2['title'] . "</option>");
		}
		?>
	</select>
	<?php
}

function getSchedules($selected,$onchangeform="",$published=0)
{
	global $db;
	if($onchangeform!="")
		$onchange = " onchange=\"document.forms['" . $onchangeform . "'].submit()\"";
	if($published==1)
		$where = " WHERE published=1 ORDER BY id";
	$res = $db->query("SELECT * FROM Schedule " . $where);
	?>
	<select name="schedule" <?=$onchange ?>>
			<?php
			while($row=mysql_fetch_array($res))
			{
				echo "<option value=\"" . $row['id'] . "\"" . ( $row['id']==$selected?" selected=\"selected\"":"") . ">" . $row['title'] . "</option>";
			}?>
	</select>
	<?php
}

function printForm($onchangeform="")
{
	if($onchangeform!="")
		$onchange = " onchange=\"document.forms['" . $onchangeform . "'].submit()\"";
	echo "<div class=\"startForm\">";
	echo "<b>Πρόγραμμα:</b>";
	getSchedules($_GET['schedule'],$onchangeform);
	echo "<b>Τμήμα:</b>";
	getDepartments($_GET['dep_id'],$onchangeform,true);
	echo "<b>Τύπος εξαμήνου:</b>";
	?>
	<select name="semestertype" <?=$onchange?>>
		<option value="0" <?=($_GET['semestertype']==0?" selected=\"selected\"" :"")?>>Όλα</option>
		<option value="1" <?=($_GET['semestertype']==1?" selected=\"selected\"" :"")?>>Χειμερινό</option>
		<option value="2"<?=($_GET['semestertype']==2?" selected=\"selected\"" :"")?>> Εαρινό </option>
	</select>
	<?php
	echo "<b>Εξάμηνο:</b>";
	getSemesters($_GET['semester'],$onchangeform);
	echo "</div>";
}



function getWhereClause($scheduleC=true,$departmentC=true,$semestertypeC=true,$semesterC=true)
{

	$where=" ";
	$semester = 0 + $_GET['semester'];
	$semestertype = 0 + $_GET['semestertype'];
	$department = 0 + $_GET['dep_id'];
	$schedule = 0 + $_GET['schedule'];
	if($schedule>0&&$scheduleC==true)
		$where .= "Submit.schedule_id=" . $schedule;

	if($department>0&&$departmentC==true)
	{
		if(strlen($where)>1)
			$where.=" AND ";
		$where .= "Lesson.department_id = " . $department;
	}
	if($semestertype>0&&$semestertypeC==true)
	{
		if($semestertype==2)
			$semestertype=0;
		if(strlen($where)>1)
			$where.=" AND ";
		$where .= "Lesson.semester%2= " . $semestertype;
	}
	if($semester>0&&$semesterC==true)
	{
		if(strlen($where)>1)
			$where.=" AND ";
		$where .= "Lesson.semester = " . $semester;
	}
	return $where;
}

function getPOSTWhereClause($scheduleC=true,$departmentC=true,$semestertypeC=true,$semesterC=true)
{

	$where=" ";
	$semester = 0 + $_POST['semester'];
	$semestertype = 0 + $_POST['semestertype'];
	$department = 0 + $_POST['dep_id'];
	$schedule = 0 + $_POST['schedule'];
	if($schedule>0&&$scheduleC==true)
		$where .= "  Submit.schedule_id=" . $schedule;

	if($department>0&&$departmentC==true)
	{
		if(strlen($where)>1)
			$where.=" AND ";
		$where .= " (Lesson.department_id = 9 OR  Lesson.department_id = " . $department. " ) ";
	}
	if($semestertype>0&&$semestertypeC==true)
	{
		if($semestertype==2)
			$semestertype=0;
		if(strlen($where)>1)
			$where.=" AND ";
		$where .= "Lesson.semester%2= " . $semestertype;
	}
	if($semester>0&&$semesterC==true)
	{
		if(strlen($where)>1)
			$where.=" AND ";
		$where .= "Lesson.semester = " . $semester;
	}
	return $where;
}



function printGrid($PrintCellFunction,$cells)
{
	global $db;
	$slots = array();
	$times = array();
	$days = array();
	$res = $db->query("SELECT Slot.*,Time.time,Day.name FROM Slot,Time,Day WHERE Slot.time_id=Time.id AND Slot.day_id=Day.id");
	while($row = mysql_fetch_array($res))
	{
		$slots[$row['time_id']][$row['day_id']]= $row['id'];
		$times[$row['time_id']] = $row['time'];
		$days[$row['day_id']] = $row['name'];
	}

	?>

	<table class="wood" cellspacing="0">
		<tr>
		<td background="styles/images/wood.jpg" height="17" COLSPAN=4> </td>
		</tr>

		<tr>

		<td background="styles/images/wood.jpg" width="17"> </td>

	<td class="woodtd">
	<table class="schedule" cellspacing="0">
    	<tr>

			<td class="heading" bgcolor="#B22222">&nbsp;</td>
			<?php
			for($i=1;$i<=sizeof($days);$i++)
				echo "<td class=\"colhead\">" . $days[$i] . "</td>";
			?>
		</tr>
		<?php
		for($i=1;$i<=6;$i++)
		{
			echo "<tr>";
			echo "<td class=\"rowhead\">" . $times[$i] . "</td>";
			for($j=1;$j<=5;$j++)
			{
				$slot_id = $slots[$i][$j];
				$PrintCellFunction($slot_id,$cells[$slot_id]);
			}
			echo "</tr>";
		}
	print("</table>");
	?>
	</td>

	<td background="styles/images/wood.jpg" width="17"> </td>

	</tr>

	<tr>
	<td background="styles/images/wood.jpg" height="17" COLSPAN=4> </td>
	</tr>

	</table>

	<?
}




?>
