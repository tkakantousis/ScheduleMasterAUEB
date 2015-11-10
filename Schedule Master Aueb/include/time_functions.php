<?php
function now_unix() //Returns the current gtm unixtimestamp
{
	return unix_time(gmdate("Y-m-d H:i:s"));
}

function now_datetime() //Returns the current gtm date-time
{
    return gmdate("Y-m-d H:i:s");
}

function unix_time($s) //Converts mysql date-time to unixtimestamp
{
	return gmmktime(substr($s, 11, 2), substr($s, 14, 2), substr($s, 17, 2), substr($s, 5, 2), substr($s, 8, 2), substr($s, 0, 4));
}

function db_time($timestamp) //Converts unix timestamp to mysql datetime
{
	return gmdate("Y-m-d H:i:s", $timestamp);
}

function unix_to_user($timestamp) //Converts unixtimestamp to datetime taking into account timezone
{
	return date("d-m-Y H:i:s", $timestamp);
}

function dbtime_to_user($datetime) //Converts datetime to unixtimestamp taking into account timezone
{
	$timestamp=unix_time($datetime);
	return date("d-m-Y H:i:s", $timestamp);
}

function second_diff($datetime1,$datetime2) //Returns the difference in seconds of two datetimes
{
	$timestamp1=unix_time($datetime1);
	$timestamp2=unix_time($datetime2);
	return $timestamp1-$timestamp2;
}

function period_diff($datetime1,$datetime2) //Returns the difference in periods of two datetimes
{
	return period_diff_unix(unix_time($datetime1),unix_time($datetime2));
}

function period_diff_unix($timestamp1,$timestamp2) //Returns the difference in periods of two unixtimestamps
{
	$diff = $timestamp1-$timestamp2;
  	$mins = floor(($diff) / 60);
  	$hours = floor($mins / 60);
  	$mins -= $hours * 60;
  	$days = floor($hours / 24);
  	$hours -= $days * 24;
  	$weeks = floor($days / 7);
  	$days -= $weeks * 7;
  	$t = "";
  	if ($weeks)
  		return "$weeks week" . ($weeks > 1 ? "s" : "");
  	if ($days)
    	return "$days day" . ($days > 1 ? "s" : "");
  	if ($hours)
    	return "$hours hour" . ($hours > 1 ? "s" : "");
  	if ($mins)
    	return "$mins min" . ($mins > 1 ? "s" : "");
  	return "< 1 min";
}
?>