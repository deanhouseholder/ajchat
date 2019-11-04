<?

if (!empty($_GET["roomname2"])){
	$_GET["roomname"] = $_GET["roomname2"];
}

if (!empty($_GET["roomname"])){
	header("Location: chat/".trim($_GET["roomname"]));
}

require("includes/config.inc.php");

require("includes/sajax.inc.php");

function suggest($roomname){

	if (!ctype_alnum($roomname)) return -1; 

	$q = "SELECT `roomname` FROM `".PREFIX."rooms` WHERE `roomname` LIKE '".$roomname."%' AND `updated` >0 LIMIT 5";
	$r = mysql_query($q);
	$c = 0;
	
	while($row = mysql_fetch_assoc($r)){
		$dat[$c++] = $row["roomname"];
	}

	if ($c>0) $data = join(",",$dat);
	else $data = "";
	
	return $data;

}

sajax_init();
sajax_export("suggest");
sajax_handle_client_request();

if (!empty($_GET["msg"])){
	if ($_GET["msg"]==1){
		$_chat["msg"] = "Please make sure your chatroom name is alphanumeric.";
	}else if ($_GET["msg"]==2){
		$_chat["msg"] = "Please make sure your chatroom name is less than 25 characters long.";
	}
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
       "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html>


<head>
	<title>ajchat</title>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="keywords" content="ajchat, chat, ajax"  />
	<meta name="description" content="ajchat is a simple ajax chat system where you can add chatrooms to your website." />

	<link rel="Stylesheet" href="screen.css" type="text/css" media="screen" />


<script type="text/javascript" language="JavaScript">
	<?
	sajax_show_javascript();
	?>
	
	function write_suggest(data){
		if (data==-1){
			document.getElementById("msg").innerHTML = "Please make sure your chatroom name is alphanumeric.";
		}else if (data!=""){
			var dat, cnames; 
			
			cnames = data.split(",");
			
			if (cnames.length<=1) dat = "<b>Suggestion</b>: ";
			else dat = "<b>Suggestions</b>: ";
			
			for (i=0;i<cnames.length;i++){
				cnames[i] = "<a href='chat/"+cnames[i]+"'>"+cnames[i]+"</a>";
			}
			
			dat += cnames.join(", ");
			
			document.getElementById("msg").innerHTML = dat;
			
			
		}else document.getElementById("msg").innerHTML = "";
	}
	

	function suggest(){
		var roomname = document.getElementById("roomname").value;
		if (roomname!="") x_suggest(roomname,write_suggest);
	}
</script>

</head>

<body>

<? include("includes/header.inc.php"); ?>

<div id="contents">

	<div class="shadow">
		<div class="box">
			<center>
			<form action="<?= $_SERVER["PHP_SELF"]; ?>" method="get"> 
						
			<p>
			<b>To goto or create a chat room (e.g. technology), enter the room name and hit Enter!</b>
				<br/>
				<br/>
				<input type="text" size="50%" maxlength="25" id="roomname" class="room" name="roomname" onkeyup="suggest();" />
				<br/>
				<input type="submit" value="Enter!" class="button" />
			</p>
			</form>

			<div class="msg" id="msg"><? if (!empty($_chat["msg"])) print $_chat["msg"]; else print "&nbsp;"?></div>
			

			<p>
			<b>&raquo; <a href="recent.php">Recent</a>: </b>
<?
	$q = "SELECT `roomname` FROM `".PREFIX."rooms` ORDER BY `updated` DESC LIMIT 5 ";
	$r = mysql_query($q) or die(mysql_error());
	
	while($row = mysql_fetch_assoc($r)){
		$recent[] = "<a href=\"chat/".$row["roomname"]."\">".$row["roomname"]."</a>";
	}
	
	print join(", ", $recent);
	
?>

			</p>
			<p><b>&raquo; View the full <a href="roomlist.php">Room List</a></b><br></p>
<?			


	$q = "SELECT `roomname` FROM `".PREFIX."rooms` WHERE `lines` > 0 ORDER BY `lines` DESC LIMIT 5";
	$r = mysql_query($q) or die(mysql_error());
	
	if (mysql_num_rows($r)>0){ 
		print "<p><b>&raquo; Popular: </b>";
	
		while($row = mysql_fetch_assoc($r)){
			$pop[] = "<a href=\"chat/".$row["roomname"]."\">".$row["roomname"]."</a>";
		}
	
		print join(", ", $pop);
	
	}
	
	
	
?>
			</p>
			
			<p class="intro">
			<span style="color: #666666">aj</span><span style="color: #4260BF">chat</span> is <i>more</i> than a chat system.
			<br/>
			<span style="font-size: smaller;">... <a href="<?= CPATH; ?>about/features.php">discover today</a> ...</span>
			
			</p>

			</center>
		</div>
	</div>


</div>


<? include("includes/footer.inc.php"); ?>

</body>

</html>
