<?

require("includes/config.inc.php");

$path = $_SERVER["REQUEST_URI"];
$_chat["help"] =  str_replace(CPATH."help/","",$path);
$_chat["help"] = strtolower(trim($_chat["help"]));

while (strrpos($_chat["help"],'/') === strlen($_chat["help"])-1){
	$_chat["help"] = substr($_chat["help"],0,strlen($_chat["help"])-1); 
}

if (empty($_chat["help"])) $_chat["help"] = "index";
else if ($path==(CPATH."help") || $path==(CPATH."help.php")){
	header("Location: ".CPATH."help/");
}

global $topic;

$topic = array(
	"index" => array("index.inc.php","topics"),
	"bbcode" => array("bbcode.inc.php","bbcode"),
	"whysignup" => array("whysignup.inc.php","why signup?"),
	"wordfilter" => array("wordfilter.inc.php","word filter"),
	"smilies" => array("smilies.inc.php","smilies"),
	"_error_" => array("error.inc.php","error 404")
);

if (!array_key_exists($_chat["help"],$topic)) $_chat["help"] = "_error_";
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
       "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html>


<head>
	<title>ajchat - help [<? print $topic[$_chat["help"]][1]; ?>]</title>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<link rel="Stylesheet" href="<?= CPATH; ?>screen.css" type="text/css" media="screen" />
</head>

<body>

<? include("includes/header.inc.php"); ?>

<div id="contents">

	<div class="shadow">
		<div class="box">
			<?
				include("helpdata/".$topic[$_chat["help"]][0]);
			?>
		</div>

	</div>


</div>


<? include("includes/footer.inc.php"); ?>

</body>

</html>