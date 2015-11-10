<?php
require_once("../include/global.php");
init(true);
stdHeader("Εμφάνιση Διαθέσιμων Slot ανά Αίθουσα");

$roomid = 0 + $_GET['id'];
$schedule = 0 + $_GET['schedule'];



    global $db;
   
    $res = $db->query("SELECT Slot.id, Day.name, Time.time FROM Slot,Day,Time WHERE Slot.time_id= Time.id
                        AND Slot.day_id = Day.id AND Slot.id
                        NOT IN (SELECT slot_id  FROM Submit WHERE room_id =".$roomid." AND schedule_id=".$schedule.");");

    while($row = mysql_fetch_array($res))
    {
        echo $row['id'];
        echo "&nbsp;";
        echo $row['name'];
        echo "&nbsp;";
        echo $row['time'];
        echo "<BR>";
    }

stdFooter();

?>