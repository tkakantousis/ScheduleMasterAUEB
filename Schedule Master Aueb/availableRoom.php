<?php
require_once("include/global.php");
init(false);
//init();
stdHeader("Εμφάνιση Κενών Αιθουσών ανά Πρόγραμμα");

$schedule = 0 + $_GET['id'];
$title = "" . $_GET['title'];
$symbol = "" . $_GET['symbol'];
$size = 0 + $_GET['size'];

?>
<form action="availableRoom.php" method="get" onsubmit="javascript:return ValidateForm2(this)">
	<table class="content" border="1">
	<tr>
	<td>Χωρητικότητα 
    <select type="text" name="symbol" size="1" >
         <option value=">" name=">">></option>
         <option value="<" name="<"><</option>
         <option value="=" name="=">=</option>
         <option value=">=" name=">=">>=</option>
         <option value="<=" name="<="><=</option>
         </select> από: </td>
	<td> <input type="text" name="size" size="20" /> </td>
	</tr>
  
    <tr>
    <td colspan="2">
    <center>
	 	<input type="submit" name="action" value="Υποβολή" />
        <?php echo "<input type=\"hidden\" name=\"id\" value=\"".$schedule."\"/>";
              echo "<input type=\"hidden\" name=\"title\" value=\"".$title."\"/>";?>
	</center>
    </td>
    </tr>
    </table>

</form>
<BR><BR><BR><BR><BR>
<?php

if($size>0){
$res = $db->query("SELECT title FROM Room where size".$symbol.$size." AND id NOT IN
                    (SELECT room_id FROM Submit WHERE schedule_id =".$schedule." )");
    if(mysql_num_rows($res)==0)
    {
        echo "Δεν υπάρχουν αίθουσες με αυτά τα κριτήρια";
    }
    else
    {

        echo "Οι αίθουσες/αμφιθέατρα που πληρούν τα κριτήρια για το $title είναι οι:";
        echo "<BR>";
        while($row = mysql_fetch_array($res))
        {

            echo $row['title'];
            echo "<BR>";

        }
    }
}

stdFooter();
?>
