<?php

require_once("include/global.php");
init(true);

$id = 0 + $_GET['id'];
if(isset($_GET['id'])&&$id>0)
{
	$res = $db->query("SELECT * FROM Schedule WHERE id=" . $id . "");
	if(mysql_num_rows($res)==0)
	{
		stdError("Error", "Λάθος στοιχεία");
	}
	$row = mysql_fetch_array($res);

	$title = "Επεξεργασία Προγράμματος";
}
else
{
	$title = "Εισαγωγή Προγράμματος";
}

stdHeader($title);
?>


<form action="actions/takeSchedule.php" method="post" onsubmit="javascript:return ValidateForm(this)">
	<table class="content">
	<?php
	if(!isset($_GET['id'])){
	echo '<tr>';
	echo '<td class="rowhead">Αντίγραφο του:</td>';
	echo '<td>';
		
		$res = $db->query("SELECT id,title FROM Schedule");
		echo "<select name=\"previous\">";
		echo "<option value=\"0\">Κανένα</option>";
		while($row=mysql_fetch_array($res))
		{
			echo "<option value=\"" . $row['id'] . "\">" . $row['title'] . "</option>";
		}

		?>input type="text" name="title" size="40" value="<? echo $row['title']; ?>" /> </td>
		<?php
		
	echo '</tr>';
	}
	
	if(isset($_GET['id'])&&$id>0)
	{
		$res = $db->query("SELECT * FROM Schedule WHERE id=" . $id . "");
		if(mysql_num_rows($res)==0)
		{
			stdError("Error", "Λάθος στοιχεία");
		}
		
		while($row=mysql_fetch_array($res))
		{
			$title1 = $row['title'];
			$comment = $row['comment'];
			$published = $row['published'];
			$created = $row['created'];
			$modified = $row['modified'];
			$publishedDate = $row['publishedDate'];
		}

  }
	
	
		
	
	echo '<tr>';
	echo '<td class="rowhead"> Τίτλος Προγράμματος: </td>';
	echo '<td> <input type="text" name="title" size="40" value="' . $title1 . '" /> </td>';
	echo '</tr>';

	
	echo '<tr>';
	echo '<td class="rowhead"> Σχόλια: </td>';
	echo '<td> <textarea name="comment" size="40" >' . $comment . '</textarea> </td>';
	echo '</tr>';

	echo '<tr>';
	echo '<td class="rowhead"> Δημοσιευμένο: </td>';
	if ($published == "1") {
		echo '<td><input type="checkbox" name="published"  checked/> </td>';
	}
	else {
		echo '<td><input type="checkbox" name="published"  /> </td>';
	}	
	echo '</tr>';


	if(isset($id)&&$id>0)
	{
		
			echo '<tr>';
			echo '<td class="rowhead"> Ημερομηνία Δημιουργίας: </td>';
			echo '<td><b>' . $created .'</b></td>';
			echo '</tr>';
			echo '<tr>';
			echo '<td class="rowhead"> Ημερομηνία επεξεργασίας: </td>';
			echo '<td><b>' . $modified . '</b></td>';
			echo '</tr>';
			
			
			echo '<tr>';
			echo '<td class="rowhead"> Ημερομηνία δημοσίευσης: </td>';
			echo '<td><b>' . $publishedDate . '</b></td>';
			echo '</tr>';
			echo '<input type="hidden" name="action" value="edit"/>';
			
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
</form>

<form action="actions/takeSchedule.php" method="post" onsubmit="javascript:return confirm('ΠΡΟΣΟΧΗ - Διαγραφή προγράμματος?')">
<?php 
		
		$res = $db->query("SELECT id,title FROM Schedule");
		echo "<select name=\"schedule\">";
		while($row=mysql_fetch_array($res))
		{
			echo "<option value=\"" . $row['id'] . "\">" . $row['title'] . "</option>";
		}
		echo "</select>";
		?>
    <input type="submit" value="Διαγραφή" />
    <input type="hidden" name="action" value="delete"/>
</form>

<?php

stdFooter();
?>
