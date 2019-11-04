<?

include("../includes/config.inc.php");

if (isset($_SESSION["lastupdated"])){
	reset($_SESSION["lastupdated"]);
	
	while (list($key, $value) = each($_SESSION["lastupdated"]) ) {
		$_c["needrefresh"][$key] = 1;
	}

}
$_c["count"] = $_SESSION["count"];



if (isset($_COOKIE[session_name()])) {
    setcookie(session_name(), '', time()-$_c["session_length"], '/');
}

session_destroy();

session_regenerate_id(true);

if (isset($_COOKIE["ajchat_sid"])) setcookie ("ajchat_sid", "", time() - $_c["session_length"],"/");

if (isset($_c["needrefresh"])){

	reset($_c["needrefresh"]);

	while (list($key, $value) = each($_c["needrefresh"]) ) {
		$_SESSION["needrefresh"][$key] = 1;
	}

}


$_SESSION["count"] = $_c["count"];

header("Location: ".CPATH);

?>