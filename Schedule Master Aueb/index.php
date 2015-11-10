<?php
//This application was designed by Ailianos Thomas, Kakantousis Theofilos and Mygdanis Ioannis
require_once("include/global.php");
$db = new database($DATABASELOCATION,$DATABASEUSER2,$DATABASEPASSWORD2,$DATABASENAME);
init(false);

stdHeader2("Καλώς Ήρθατε στο Schedule Master Aueb");
$_ENV['Ayearcolor'] = $Ayearcol;
$_ENV['Byearcolor'] = $Byearcol;
$_ENV['Cyearcolor'] = $Cyearcol;
$_ENV['Dyearcolor'] = $Dyearcol;
$temp = 0 + $_POST['temp'];

$semester= 0 + $_POST['semester'];
$department= 0 + $_POST['dep_id'];
$counter+= $_POST['counter'];
$counter2 += $_POST['counter2'];
$schedule= 0 + $_POST['schedule'];
if($temp!=$schedule){$counter2=0;}
$temp = $schedule;
$res = $db->query("select count(*) as coun from Schedule where published=1");
$row = mysql_fetch_array($res);
$num = $row['coun'];
if($num==1)$check=2;
else $check=1;
if($counter==0)  //Select semester type. As soon as the schedule is selected, the type is automatically added. Then the user can change the semester type.
{	
	$res = $db->query(" SELECT semester	FROM Submit, Lesson	WHERE Submit.lesson_id = Lesson.id 	AND Submit.schedule_id = (SELECT id FROM Schedule WHERE published=1 ORDER BY id ASC LIMIT 1) limit 1");
	$row = mysql_fetch_array($res);
	if($row['semester']==1 || $row['semester']==3 || $row['semester']==5 || $row['semester']==7 ) 
	$_POST['semestertype']=1;  //Proepilogi xeimerinwn mathimatwn
	else $_POST['semestertype']=2;
	$counter++;
	$counter2++;
}
else
{		if($counter2==0 && $counter!=$check){
		$res = $db->query(" SELECT semester	FROM Submit, Lesson	WHERE Submit.lesson_id = Lesson.id 	AND Submit.schedule_id = ".$schedule." limit 1");
		$row = mysql_fetch_array($res);
		if($row['semester']==1 || $row['semester']==3 || $row['semester']==5 || $row['semester']==7 ) 
		$_POST['semestertype']=1; 
		else $_POST['semestertype']=2;
		}
		$counter2++;
		
}
$counter++;

$selectall = "" . $_POST['all'];
$unselectall = "" . $_POST['notall'];


?>

<table border="0" width = "100%">
<tr>
	<td width="1%">
	<a href = "about.php" ><img src="styles/images/logo.png" border="0" alt="info" width="150px"/></a>
	</td>
	<td align="center">
	<a href = "http://www.aueb.gr" ><img src="styles/images/hermes.gif"  border="0" alt="A.U.E.B"/></a>
	
<div class="abouttable">
                 <table>
                <tr>
                    <td>
                        <a href = "about.php" >Επικοινωνία</a> <br>
                        <a href = "about.php" ><img src="styles/images/info8.png" border="0" alt="info" height ="30px" width="30px"/></a>
                    </td>

                </tr>
            </table>
                </div>
                
	<br />
	<font size="+3"><strong>Οικονομικό Πανεπιστήμιο Αθηνών</strong></font>
	
	</td>
</tr>
</table>


<br />
<table class="content">
	<tr>
		<td colspan="2" valign="top">
		<div class="formdiv">
			<form method="post" action="index.php" name="index">
			<div class="startForm">
			<?
				$onchange = " onchange=\"document.forms['index'].submit()\"";
				echo "<b>Πρόγραμμα:</b>";
				getSchedules(0 + $_POST['schedule'],"index",1);
				echo "<br /><b>Τμήμα:</b>";
				getDepartments( 0 + $_POST['dep_id'],"index");
				echo "<br /><b>Τύπος εξαμήνου:</b>";
				?>
				<select name="semestertype" <?=$onchange?>>
					<option value="0" <?=(0 + $_POST['semestertype']==0?" selected=\"selected\"" :"")?>>Όλα</option>
					<option value="1" <?=(0+ $_POST['semestertype']==1?" selected=\"selected\"" :"")?>>Χειμερινό</option>
					<option value="2"<?=(0 +$_POST['semestertype']==2?" selected=\"selected\"" :"")?>> Εαρινό </option>
				</select>
				<?php
				echo "<br /><b>Εξάμηνο:</b>";
				getSemesters(0 + $_POST['semester'],"index");
                echo "<br /><b>Μαθήματα:</b><br />
                <div class=\"scroll\">";
                if($selectall=="Select All")
                	getLessons(0 + $_POST['dep_id'],0 + $_POST['semestertype'],0 + $_POST['semester'],"index",2);
                else if($unselectall=="Un-Select All")
                	getLessons(0 + $_POST['dep_id'],0 + $_POST['semestertype'],0 + $_POST['semester'],"index",3);
                else 
                	getLessons(0 + $_POST['dep_id'],0 + $_POST['semestertype'],0 + $_POST['semester'],"index",1);
				echo "</div>";
			?>
					<div class="button1"><input type="submit" value="Καταχώρηση" /></div>
	                <div class="button23">
	                	<input type="submit" value="Select All"  name="all"/>
	                	<input type="submit" value="Un-Select All"  name="notall"/>
	                </div>
                </div>
				<input type="hidden"  name="counter" value="<?=$counter?>"/>
				<input type="hidden"  name="counter2" value="<?=$counter2?>"/>
				<input type="hidden"  name="temp" value="<?=$temp?>"/>

			</form>
        </div>
		</td>
        <td valign="top">
			<?php
				$lessons = $_POST['lessons'];
				for($i=0;$i<sizeof(sizeof($lessons));$i++)
				{
					$lessons[$i] = 0 + $lessons[$i];
				}
				if(is_array($lessons)&&sizeof($lessons)>0)
				{
					$res = $db->query("SELECT
								Lesson.id AS lesid,
								Submit.id,
								Lesson.semester,
								Lesson.department_id,
								Lesson.title AS lesTitle,
								Lesson.professor as prof,
								rom.title AS romTitle,
								slot_id,
                                                                comments
							FROM
								Submit

							INNER JOIN Room rom ON rom.id=Submit.room_id
							INNER JOIN Lesson ON Lesson.id=lesson_id 
							WHERE 
								Lesson.id IN (" . implode(",",$lessons) . ")
								AND
								schedule_id=" . $schedule . "
								");

					$cells=array();
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
												$entry['professor']= $row['prof'];
						$cells[$row['slot_id']][]= $entry;
					}
					printGrid("printScheCell",$cells);
				}
				else
				{
					echo "<img src=\"styles/images/boardindex.jpg\" alt=\"board\" style=\"margin-left:100px;\"/>";
				}

			?>
            <center>
            <div>

           <?php
		   
				
                echo "<form target=\"_blank\" action=\"pdfmaker.php\" method=\"post\" name=\"pdfmaker\">";
				
				$lessons = $_POST['lessons'];
				if(is_array($lessons))
				{
					foreach ($lessons as $value)
						echo "<input type=\"hidden\" name=\"lessons[]\" value=\"" . $value  . "\" />";
				}
				?>
								<input type ="hidden" name="dep_id" value="<?=$department?>"/>
								<input type ="hidden" name="semester" value="<?=$semester?>"/>
								<input type="hidden" name="schedule" value="<?=$schedule?>" />
                                <input type="submit" value="Αποθήκευση/Εκτύπωση Προγράμματος σε PDF"/>
				</form>
				
				
				 
				
                <br></br>
                <table bgcolor="#000000">
            <tr><th bgcolor="#FFFFFF" colspan="2"><center><b>Appendix</b></center></th></tr>
            <?
               echo "<tr><td bgcolor=\"". $_ENV['Ayearcolor']."\">Κόκκινο Χρώμα:</td><td bgcolor=\"#FFFFFF\"> 1ο Έτος</td></tr>";
               echo "<tr><td bgcolor=\"". $_ENV['Byearcolor']."\">Μπλέ Χρώμα:</td><td bgcolor=\"#FFFFFF\"> 2ο Έτος</td></tr>";
               echo "<tr><td bgcolor=\"". $_ENV['Cyearcolor']."\">Κίτρινο Χρώμα:</td><td bgcolor=\"#FFFFFF\"> 3ο Έτος</td></tr>";
               echo "<tr><td bgcolor=\"". $_ENV['Dyearcolor']."\">Πράσινο Χρώμα:</td><td bgcolor=\"#FFFFFF\"> 4ο Έτος</td></tr>";
            ?>
            </table>
               
				
            </div>
	 </center>
	
            </td>
	</tr>

</table>


<?php
stdFooter();

function printScheCell($slot_id,$cell)
{
	global $schedule;
    global $HTTP_POST_VARS;

	echo "<td class=\"scheduletd\">&nbsp;";
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


			echo "<tr><td class=\"innertd\" bgcolor=\"" . $semestercolor . "\">";
            echo "<div><img src=\"styles/images/pin.png\" alt=\"P\"/></div>";
			print("" . $entry['lesson'] . "<br /> <div class=\"prof\">".$entry['professor']."</div>" . $entry['room'] . "<br />");
                        print("<div class=\"comments\">" . $entry['comments'] ."");
						
			echo "</td></tr>";
		}
		echo "</table>";
	}
	echo "</td>";
}


function getLessons($department, $semestertype, $semester, $onchangeform="", $lessonsType)
{
	global $db;

	$department = 0 + $_POST['dep_id'];
	$semestertype = 0 + $_POST['semestertype'];
	$semester = 0 + $_POST['semester'];
	$lessons = $_POST['lessons'];
	$where = getPOSTWhereClause(false);
	
	if(strlen($where)>1)
		$where = " WHERE " . $where;
	
	$res = $db->query("SELECT Lesson.id, Lesson.title, Department.title as tit, semester, department_id FROM Lesson INNER JOIN Department ON Department.id = Lesson.department_id   " . $where . " ORDER BY department_id ASC, semester ASC, Lesson.title ASC");
        if($semestertype==1 && ($semester==2 || $semester==4 || $semester==6 || $semester==8))
        {
            echo "Το εξάμηνο που διαλέξατε δεν είναι χειμερινό";
            return;
        }
        if( $semestertype==2 && ($semester==1 || $semester==3 || $semester==5 || $semester==7))
        {
            echo "Το εξάμηνο που διαλέξατε δεν είναι εαρινό";
            return;
        }
        $previousSemester=0;
		$depid = $row['department_id'];
        if($lessonsType == 1){
		while($row = mysql_fetch_array($res))
		{
		    $isChecked=false;
				if($department==0)
				{
				
					if($row['department_id']!=$depid)
					{
						if($row['department_id']==9)
						{	echo "<br/><div class=\"tmima\">Κοινά Μαθήματα: </div>"; }
						else {echo "<br/><div class=\"tmima\">Τμήμα: ".$row['tit']."</div>"; }
						$depid = $row['department_id'];
					}
				}
		    if(is_array($lessons) && in_array($row['id'],$lessons))
		            $isChecked=true;
		    if($row['semester']!=$semester)
		            echo "<hr />";
		    $semester=$row['semester'];
		    if($isChecked==true)
		    echo "<div class=\"lessonchecked\"><input type=\"checkbox\" id=\"0\" " . ($isChecked?"checked = \"yes\"":"") . " name=\"lessons[]\" value=\"" . $row['id'] . "\" />" . htmlspecialchars($row['title']) . "</div>\n";
		    else
		    echo "<div class=\"lessonnotchecked\"><input type=\"checkbox\" id=\"0\"  " . ($isChecked?"checked = \"no\"":"") . " name=\"lessons[]\" value=\"" . $row['id'] . "\" />" . htmlspecialchars($row['title']) . "</div>\n";


             }
      } elseif($lessonsType == 2) {
		while($row = mysql_fetch_array($res))
			{
			    $isChecked = true;
					if($department==0)
					{
						if($row['department_id']!=$depid)
						{
							if($row['department_id']==9)
							{	echo "<br/><div class=\"tmima\">Κοινά Μαθήματα: </div>"; }
							else {echo "<br/><div class=\"tmima\">Τμήμα: ".$row['tit']."</div>"; }
							$depid = $row['department_id'];
						}
					}
			    if($row['semester']!=$semester)
				    echo "<hr />";
			    $semester=$row['semester'];
			    echo "<div class=\"lessonchecked\"><input type=\"checkbox\" " . ($isChecked?"checked = \"yes\"":"") . " name=\"lessons[]\" value=\"" . $row['id'] . "\" />" . htmlspecialchars($row['title']) . "</div>\n"; 
                }
      } elseif($lessonsType == 3){
		while($row = mysql_fetch_array($res))
		{
		    $isChecked=false;
				if($department==0)
				{
					if($row['department_id']!=$depid)
					{
						if($row['department_id']==9)
						{	echo "<br/><div class=\"tmima\">Κοινά Μαθήματα: </div>"; }
						else {echo "<br/><div class=\"tmima\">Τμήμα: ".$row['tit']."</div>"; }
						$depid = $row['department_id'];
					}
				}
		    if($row['semester']!=$semester)
		            echo "<hr />";
		    $semester=$row['semester'];
		    echo "<div class=\"lessonnotchecked\"><input type=\"checkbox\" " . ($isChecked?"checked = \"no\"":"") . " name=\"lessons[]\" value=\"" . $row['id'] . "\" />" . htmlspecialchars($row['title']) . "</div>\n";
		 }

      }
}
?>
