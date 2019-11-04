<?
error_reporting(0);
require("includes/config.inc.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
       "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
	<title>ajchat - roomlist</title>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<link rel="Stylesheet" href="<?= CPATH; ?>screen.css" type="text/css" media="screen" />
</head>

<body>
<? include("includes/header.inc.php"); ?>
<div id="contents">

	<div class="shadow">
		<div class="box">

			<h1>Room List <small><small><small><a href="<?= $_SERVER["PHP_SELF"] ?>"><u>[refresh]</u></a></small></small></small></h1>
<?
$time = time();

$q = "SELECT `roomname`, `updated` FROM `".PREFIX."rooms` WHERE `updated` > 0 ORDER BY `updated` DESC";

$r = mysql_query($q) or die(mysql_error());
$num = mysql_num_rows($r);

print "<h2>".$_GET['s']." (".$num." Chatrooms)</h2>";

print "<div id=\"infobox\" >";
print "<ul>";

if ($num<=0) print "There is no chatroom starting with <b>".$_GET['s']."</b>.";

while($row = mysql_fetch_assoc($r)){

	$filepath = "chatdata/".$row["roomname"].".dat";

	if (!file_exists($filepath)) continue;

	print "<li>";

	print "<a href=\"chat/".$row["roomname"]."\">".$row["roomname"];
	print " <span class=\"info\">(last updated in ";

	$row["updated"] = $time - round($row["updated"],0);

	if ($row["updated"]<60) print $row["updated"]." sec";
	else{

		if ($row["updated"]>3600){
			$hrs = floor($row["updated"]/3600);

			if ($hrs>=24){
				$days = floor($hrs / 24);
				print $days;
				if ($days<=1) print " day ";
				else print " days ";

				$hrs = $hrs%24;
			}

			print $hrs;
			if ($hrs<=1) print " hr ";
			else print " hrs ";

		}else $hrs = 0;

		$min = floor((($row["updated"] - $hrs*3600)%3600)/60);
		print $min." min";
	}
	print ")</span></a>";

	print "<blockquote class=\"info\">";

		$tmp = file($filepath);

		if (empty($tmp)){
			print "&nbsp;";
		}else{

			for ($i=count($tmp)-1;$i>=count($tmp)-3 && $i>=0;$i--){
				$dat = split("<~>",$tmp[$i]);
				print "<b>[".$dat[2]."]</b> ".$dat[3]."<br/>\n";
			}
		}

	print "</blockquote></li>";
}

print "</ul>";
print "</div>";

?>





		</div>

	</div>


</div>


<? include("includes/footer.inc.php"); ?>

</body>

</html>