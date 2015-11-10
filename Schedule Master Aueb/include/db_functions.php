<?php
require_once("settings.php");
require_once("time_functions.php");
class database
{
	var $dbuser;
	var $dbpassword;
    var $dblocation;
	var $dbname;


	function database($dblocation,$dbuser,$dbpassword,$dbname)
	{
		$this->dbuser=$dbuser;
		$this->dbpassword=$dbpassword;
		$this->dblocation=$dblocation;
		$this->dbname=$dbname;
		$this->connect();
   	}

   	function connect()
   	{
   		if (!mysql_connect($this->dblocation,$this->dbuser,$this->dbpassword))
   		{
			$this->writeSQLError("Could not connect to database." . mysql_error() );
			die();
   		}

   		if(!mysql_select_db($this->dbname))
   		{
   			$this->writeSQLError("Could not select database." . mysql_error() );
   			die();
   		}
   		
		mysql_query('set character set UTF8');
		mysql_query("SET NAMES 'utf8'");
		
   	}

   	function writeSQLError($error)
   	{
   		global $SHOWMYSQLERRORS,$LOGMYSQLERRORS,$SERVERPATH,$LOGPATH;
   		if ($SHOWMYSQLERRORS)
	   	{
	   		print($error);
	   	}
	   	else
	   	{
	   		print("Database error .Contact the administrator.");
	   	}
	   	if($LOGMYSQLERRORS)
	   	{
			$file = fopen($SERVERPATH . "/" . $LOGPATH,"a");
			fwrite($file,$error . " \n \n ");
			fclose($file);
   		}
   	}

   	function sqlEsc($query)
   	{
   		return "'" . mysql_real_escape_string($query) . "'";
   	}

   	function query($query)
   	{
   		global $ADMINEMAIL;
   		$res = @mysql_query($query);
   	 	if(!$res)
   	 	{
   	 		$getValues = "";
			$postValues = "";
			foreach ($_GET as $key => $val)
			{
				$getValues .= "&" . $key . "=" . $val;
			}
			foreach ($_POST as $key => $val)
			{
				$postValues .= "&" . $key . "=" . $val;
			}
			
			$string = "Could not query database." .
   	 								"\nDate:'" . now_datetime() . "'" .
   	 								"\nFile:'" . $_SERVER['PHP_SELF'] . "'" .
   	 								"\nGET:'" . $getValues . "'" .
   	 								"\nPOST:" . $postValues . "'" .
   	 								"\nError: '" .  mysql_error() . "'" .
   	 								"\nQuery:'" . $query . "'" .
   	 								"\n----------------------------------------------------------------------------------------------------------------";
			echo "a" . mail  ($ADMINEMAIL  , "Mysql error at schedule.aueb.gr"  , $string);
   	 		$this->writeSQLError($string);
   	 		die();
   	 	}
   	 	return $res;
   	}
}
?>
