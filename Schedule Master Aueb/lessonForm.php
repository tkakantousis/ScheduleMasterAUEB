<?php

require_once("include/global.php");
init(true);

$id = 0 + $_GET['id'];
if(isset($_GET['id']))
{
	$res = $db->query("SELECT * FROM Lesson WHERE id=" . $id . "");
	$row = mysql_fetch_array($res);

	$title = "Τροποποίηση Μαθήματος";
}
else
{
	$title = "Πρόσθεση Μαθήματος";
}

stdHeader($title);

?>


<form action="actions/takelesson.php" method="post" onsubmit="javascript:return ValidateForm(this)">
	<table class="content">
	<tr>
	<td class="rowhead"> Τίτλος: </td>
	<td> <input type="text" name="title" size="40" value="<? echo $row['title']; ?>" /> </td>
	</tr>

	<tr>
	<td class="rowhead"> Διδάσκων: </td>
	<td> <input type="text" name="professor" size="40" value="<? echo $row['professor']; ?>" /> </td>
	</tr>

	<tr>
	<td class="rowhead"> Φοιτητές: </td>
	<td><input type="text" name="size" size="40" value="<? echo $row['size']; ?>" /> </td>
	</tr>

	<tr>
	<td class="rowhead"> Εξάμηνο: </td>
	<td>
	<?php getSemesters($row['semester']); ?>
	</td>
	</tr>

	<tr>
	<td class="rowhead"> Συχνότητα: </td>
	<td>
	<select name="slots">
		<?php
		for ($i=1; $i<=12; $i++) {
			if ($i == $row['n_Slot'])
				print("<option value=\"". $i ."\" selected=\"selected\">" . $i . "</option>");
			else
				print("<option value=\"". $i ."\" >" . $i . "</option>");
		}
		?>
	</select>
	</td>
	</tr>

	<tr>
	<td class="rowhead"> Τμήμα: </td>
	<td>
	<?php getDepartments($row['department_id'],"",true); ?>
	</td>
	</tr>


	<?php
	if(isset($id))
	{
		echo "<input type=\"hidden\" name=\"id\" value=\"" . $id . "\" />";
	}
	?>

	<tr>
	<td colspan="2">
	<center>
	 	<input type="submit" value="Καταχώρηση" />
	</center>
	</td>
	</tr>


	</table>
	<input type="hidden" name="ref" value="<?=$_SERVER['HTTP_REFERER']?>" />
</form>

<form action="actions/takelesson.php" method="post" onsubmit="javascript:return ValidateForm(this)">
<?php getLesson($row['id']);?>
    <input type="submit" value="Διαγραφή" />
    <input type="hidden" name="action" value="delete"/>
    <input type="hidden" name="ref" value="<?=$_SERVER['HTTP_REFERER']?>" />
</form>


<?php

stdFooter();
?>
