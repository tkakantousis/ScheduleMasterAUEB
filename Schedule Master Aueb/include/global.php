<?php
require_once("db_functions.php");
require_once("output.php");
require_once("loginFunctions.php");




$db = new database($DATABASELOCATION,$DATABASEUSER,$DATABASEPASSWORD,$DATABASENAME);
$res = mysql_query("SELECT * FROM Department");
if($res==null)
{
	header( 'Location: install.php' ) ;
}
date_default_timezone_set("Europe/Athens");


function init($require_login=false)
{
	global $SITE_ONLINE,$CONFIG,$db,$CURUSER;

	$is_logged = userlogin();


	if(!$is_logged && $require_login)
	{
		$url = $_SERVER["REQUEST_URI"];
		header("Refresh: 0; url=" . "loginForm.php?returnto=" . $url);
		exit();
	}
}

function ToHTML($strValue)
{
	return htmlspecialchars($strValue);
}

function ToURL($strValue)
{
	return urlencode($strValue);
}

function WriteLog($info)
{
	global $db;
	$db->query("INSERT INTO Log (created,msg) VALUES(" .  $db->sqlEsc(now_datetime()) . "," . $db->sqlEsc($info) . ")");
}
?>
