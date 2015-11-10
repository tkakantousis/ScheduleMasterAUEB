<?php
require_once("include/global.php");
init(true);
stdHeader("Εμφάνιση Μη Εισαγμένων Μαθημάτων σε Πρόγραμμα");

$schedule = 0 + $_GET['schedule'];
$department = 0 + $_GET['dep_id'];
$semester = 0 + $_GET['semester'];

	$where1 = getWhereClause(false);
	if(strlen($where1)>2)
		$where1 = " WHERE " . $where1;
	$where2 = getWhereClause();
	if(strlen($where2)>2)
		$where2 = " WHERE " . $where2;
$res = $db->query("
					SELECT * FROM 
							(	
								SELECT 
									COUNT(*) AS inserted 
								FROM Submit 
								INNER JOIN Lesson ON Lesson.id=Submit.lesson_id
								". $where2 . "
							) inserted,
							(
								SELECT 
									SUM(n_Slot) AS total 
								FROM Lesson 
								" . $where1 . "
							) total"
				);

$row = mysql_fetch_array($res);
$total = $row['total'];
$inserted=$row['inserted'];


	$where1 = getWhereClause(false);
	if(strlen($where1)>2)
		$where1 = " AND " . $where1;

$res2 = $db->query("SELECT 
						Lesson.id AS lessonid,
						Lesson.semester,
						Lesson.title AS lessontitle, 
						Lesson.n_Slot AS lessonslots,
						Department.id AS Departmentid,
						Department.title AS Departmenttitle,
						if(a.counter IS NULL,'0',a.counter) AS current
                    FROM 
                    	Lesson
                    INNER JOIN Department ON Department.id=Lesson.department_id
                    LEFT JOIN (
               						SELECT 
               							lesson_id,COUNT(lesson_id) AS counter
                      				FROM Submit
                      				WHERE 
                      					schedule_id =".$schedule." 
                      				GROUP BY lesson_id
                      			) a ON a.lesson_id = Lesson.id
                    WHERE 
                    	(
	                    	a.counter IS NULL
	                    	OR
	                    	a.counter<Lesson.n_Slot
                    	)
                    	" . $where1 . "
                    ORDER BY Department.id ASC,Lesson.semester ASC,Lesson.title ASC
					");


?>


<form method="get" action="restoflessons.php" name="restoflessons">
	<?php printForm("restoflessons");?>
</form>


<table class="lessons"  bgcolor="#000000">
        <tr><td class="colhead" colspan="4" bgcolor="#00FF00">ΠΡΟΓΡΑΜΜΑ ΟΛΟΚΛΗΡΩΜΕΝΟ ΚΑΤΑ: <?=$inserted?> / <?=$total?></td></tr>
        <tr><td class="colhead" bgcolor="#FFFFFF">Τμήμα</td><td class="colhead" bgcolor="#FFFFFF">Εξάμηνο</td><td class="colhead" bgcolor="#FFFFFF">Μάθημα</td><td class="colhead" bgcolor="#FFFFFF">Slots</td></tr>
        <?php
        $currentDep = 0;
        while($row=mysql_fetch_array($res2))
        {   
        	 echo "<tr>";
        	if($currentDep!=$row['Departmentid'])
        	{
        		echo   "<td bgcolor=\"#FFFFFF\">" . $row['Departmenttitle'] . "</td>";
        		$currentDep = $row['Departmentid'];
        	}
        	else
        		echo   "<td bgcolor=\"#FFFFFF\"></td>";
        		
         echo "<td bgcolor=\"#FFFFFF\">" . $row['semester'] . "</td><td bgcolor=\"#FFFFFF\">".$row['lessontitle']."</td><td bgcolor=\"#FFFFFF\">" . $row['current'] . "/" . $row['lessonslots'] ."</td></tr>";

        }?>
        
        
      
</table>
<?php 
stdFooter();
?>





