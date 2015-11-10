<?php
require_once("include/global.php");
// apo edw ksekinaei

stdHeader2("Είσοδος Χρήστη");

?>


<br>
<form action="actions/takelogin.php" method="post">
<table class="content">
	<tr><td class="rowhead">Username</td><td><input type="text" name="username" /></td></tr>
	<tr><td class="rowhead">Password</td><td><input type="password" name="password" /></td></tr>
<?
	echo "<input type=\"hidden\" name=\"returnto\" value= \"" . $_GET['returnto'] . "\" />";
?>
	<tr><td colspan="2"><input type="submit" value="Είσοδος!" /></td></tr>
</table>	
</form>

<?
stdFooter();
?>
