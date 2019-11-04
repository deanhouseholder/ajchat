<?

require("includes/config.inc.php");

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
       "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html>


<head>
	<title>ajchat - recent</title>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<link rel="Stylesheet" href="<?= CPATH; ?>screen.css" type="text/css" media="screen" />
</head>

<body>

<? include("includes/header.inc.php"); ?>

<div id="contents">

	<div class="shadow">
		<div class="box">
		
			<h1>Recently Updated Chatrooms <a href="recent.php"><img src="images/tango/emblem-symbolic-link.gif" alt="Refresh" /></a></h1>
			
			<?
			
				$time = time();
				$search = $time - 60*60*1; // EDIT HERE TO CHANGE "HOW RECENT"
				
				$q = "SELECT `roomname`, `updated` FROM `".PREFIX."rooms` WHERE `updated` > $search ORDER BY `updated` DESC LIMIT 20";
				
			
				$r = mysql_query($q) or die(mysql_error());
				
				print "<div id=\"infobox\" >";
				print "<ul>";
				
				if (mysql_num_rows($r)==0) print "No rooms were updated in the last one hour."; // REMEMBER TO EDIT HERE ALSO
				
				while($row = mysql_fetch_assoc($r)){
					
					//print "<div class=\"infobox\">";
					print "<li>";
					
					print "<a href=\"chat/".$row["roomname"]."\">".$row["roomname"];
					print " <span class=\"info\">(last updated in ";
					
					$row["updated"] = $time - round($row["updated"],0);
					
					if ($row["updated"]<60) print $row["updated"]." sec";
					else{
						
						if ($row["updated"]>3600){
							$hrs = floor($row["updated"]/3600);
							//$hrs = round(($row["updated"]/3600),0);
							print $hrs;
							if ($hrs==1) print " hr ";
							else print " hrs ";
						}else $hrs = 0;
						
						//$hrs = floor($row["updated"]/3600);
						
						$min = floor((($row["updated"] - $hrs*3600)%3600)/60);
						print $min." min";
					}
					print ")</span></a>";
					
					print "<blockquote class=\"info\">";
					
					$filepath = "chatdata/".$row["roomname"].".dat";
					$tmp = file($filepath);
					
					for ($i=count($tmp)-1;$i>=count($tmp)-3 && $i>=0;$i--){
						$dat = split("<~>",$tmp[$i]);
						if ($dat[2]!="<s>") print "<b>[".$dat[2]."]</b> ".$dat[3]."<br/>\n";
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