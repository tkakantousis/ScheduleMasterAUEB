<?php

require_once("include/global.php");
init(true);

$id = 0 + $_GET['id'];
if(isset($_GET['id']) && $id>0)
{
	$res = $db->query("SELECT * FROM Room WHERE id=" . $id . "");
    if(mysql_num_rows($res)==0)
	{
		stdError("Error", "Λάθος Στοιχεία");
	}
	$row = mysql_fetch_array($res);
	$title = "Τροποποίηση/Διαγραφή Αίθουσας";
}
else
{
	$title = "Εισαγωγή Αίθουσας";
}

stdHeader($title);
?>


<form action="actions/takeroom.php" method="post" onsubmit="javascript:return ValidateForm(this)">
	<table class="content" border="1">
	<tr>
	<td class="rowhead"> Τίτλος </td>
	<td> <input type="text" name="title" size="40" value="<? echo $row['title']; ?>" /> </td>
	</tr>

    <tr>
	<td class="rowhead"> Χωρητικότητα </td>
	<td> <input type="text" name="size" size="40" value="<? echo $row['size']; ?>" /> </td>
	</tr>

      <tr>
	<td class="rowhead"> Τμήμα </td>
	<td>
	<?php getDepartments($row['department_id']); ?>
	</td>
	</tr>


    <tr>
	<td colspan="2">
	<center>
	 	<input type="submit" name="action" value="Καταχώρηση" />
        <?php
        if(isset($_GET['id']) && $id>0)
		echo "<input type=\"hidden\" name=\"id\" value=\"" . $id . "\" />";
	
	?>
	</center>
	</td>
	</tr>

	</table>
        
 <input type="hidden" name="ref" value="<?=$_SERVER['HTTP_REFERER']?>" />
</form>


<form action="actions/takeroom.php" method="post" onsubmit="javascript:return ValidateForm(this)">
<?php getRooms($row['id']);?>
    <input type="submit" value="Διαγραφή" />
    <input type="hidden" name="action" value="delete"/>
     <input type="hidden" name="ref" value="<?=$_SERVER['HTTP_REFERER']?>" />
</form>

<?php

stdFooter();
?>
