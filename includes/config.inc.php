<?

require("config_vars.inc.php");

global $ac_link;
global $ac_link_r;

$ac_link = mysql_connect($_c["mysql_host"],$_c["mysql_user"],$_c["mysql_pass"]) or die("Unable to connect to database.");
$ac_link_r = mysql_select_db($_c["mysql_db"],$ac_link) or die("Could not select database.");

//ini_set('session.save_path','sessions');


unset($_c["mysql_host"],$_c["mysql_user"],$_c["mysql_pass"],$_c["mysql_db"]);

session_name("ajchat"); 

if (isset($_COOKIE["ajchat_sid"])){
	if (strlen($_COOKIE["ajchat_sid"])==32) session_id($_COOKIE["ajchat_sid"]);
}


session_start();

if (!isset($_SESSION["login"])){
	$_SESSION["maxlines"] = 25;
	$_SESSION["dateformat"] = 1;
	$_SESSION["displayname"] = "guest";
	$_SESSION["timezone"] = 0;
	$_SESSION["time"] = time();
	$_SESSION["login"] = false;
	
	if (!isset($_SESSION["count"])) $_SESSION["count"] = 0;
	
	$_c["login"] = false;
}


if (isset($_SESSION["username"])==false || isset($_SESSION["userid"])==false){
	$_c["login"] = false;
}else{
	$q = "SELECT `username` from `".PREFIX."users` WHERE `id` = '".$_SESSION["userid"]."'";

	$r = mysql_query($q);
	
	$row = mysql_fetch_assoc($r);
	
	if ($row["username"]!=$_SESSION["username"]){
		$_c["login"] = false;
	}else $_c["login"] = $_SESSION["login"];
}


//ob_start ("ob_gzhandler");

?>