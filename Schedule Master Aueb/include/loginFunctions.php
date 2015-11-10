<?php
include $SERVER['DOCUMENT_ROOT'] . "/include/settings.php";

function userlogin() 
{
	global $CREATORUSER,$CREATORPASS;
	if (empty($_COOKIE["username"]) || empty($_COOKIE["pass"])) 
	{
		return false;
	}

	if ($_COOKIE["username"] == $CREATORUSER && $_COOKIE["pass"] == md5($CREATORPASS)) {

		return true;
	}

	return false;
}

function logincookie($username, $password) {
	$md5 = md5($password);
	setcookie("username", $username, 0, "/");
	setcookie("pass", $md5, 0, "/");
}

function logoutcookie()
{
	setcookie("username", "", 0x7fffffff, "/");
	setcookie("pass", "", 0x7fffffff, "/");
}

function hash_pad($hash) {
	return str_pad($hash, 20);
}
?>
