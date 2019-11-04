<?

if (!isset($_GET["roomname"])) die("Error.");
else if (!ctype_alnum($_GET["roomname"])) die("Invalid Roomname.");

require("includes/config.inc.php");
require("includes/functions_msg.inc.php");

$filepath = "chatdata/".$_GET["roomname"].".dat";

if (!file_exists($filepath)) die("Data cannot be found.");

$lines = file($filepath);

//header('Content-type: text/xml; charset=UTF-8;');
//header('Content-Disposition: attachment; filename="'.$_GET["roomname"]."_log.xml".'"');

$clines = $_SESSION["maxlines"];

$addtime = $_SESSION["timezone"]*3600;

if (count($lines)==0) $data = "There is currently no chat data.";
else{
	$lines = array_reverse(array_slice($lines, -1 * $clines));

print "<?xml version=\"1.0\" encoding=\"UTF-8\" ?>\n";
print "<Ajchat>\n";

	for ($i=0;$i<count($lines);$i++){	
		$temp = explode("<~>",$lines[$i]);
	
		print "<Message ";
		
		if ($_SESSION["dateformat"]!=0)
			print "TimeDate=\"".gmdate($_c["dateformat"][$_SESSION["dateformat"]],$temp[1]+$addtime)."\"";
		else print "Timestamp=\"$temp[1]\"";
		
		print " User=\"$temp[2]\">";	
		print str_replace("\n","",$temp[3]);
		print "</Message>\n";
	
	}

/*	
	// OLD FORMAT
	
	print "<html>\n";
	print "<head>\n";
	
	print "<style>\n";
	 
	readfile("bbcode.css");
	
	print "</style>\n";
	print "</head>\n";



	for ($i=0;$i<count($lines);$i++){				
		
		//	Format: 
		//	color<~>datetime<~>handle<~>msg\n
		
		$temp = explode("<~>",$lines[$i]);
		print "<span style='color: #".$temp[0]."'>";



		print "[".$temp[2]."]</span> ".$temp[3]."<br/>\n";
	}	
	
	print "</html>";
*/



print "</Ajchat>\n";

	
}


?>