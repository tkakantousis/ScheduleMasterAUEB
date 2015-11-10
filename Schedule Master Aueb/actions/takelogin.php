<?
require_once("../include/global.php");
if(!isset($_POST['username'])||!isset($_POST['password']))
	stdError("Error","Username or password missing");
$password="" . $_POST['password'];
$username="" . $_POST['username'];


if ($CREATORUSER == $username && $CREATORPASS == $password) 
{

	logincookie($username, $password);
}
else {
	stdError("Login Failed!","Wrong User Name! OR Password!!");
}


if (!empty($_POST["returnto"]))
	header("Refresh: 0; url=" . $_POST["returnto"]);
else
	header("Refresh: 0; url=../index.php");
?>
