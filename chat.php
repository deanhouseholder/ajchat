<?

require("includes/config.inc.php");

$path = $_SERVER["REDIRECT_URL"];
//$path = $_SERVER["REQUEST_URI"];
$_chat["name"] =  str_replace(CPATH."chat/","",$path);
$_chat["name"] = strtolower(trim($_chat["name"]));

//remove slashes from end of name
$needf = false;
while (strrpos($_chat["name"],'/') === strlen($_chat["name"])-1){
	$_chat["name"] = substr($_chat["name"],0,strlen($_chat["name"])-1); 
	$needf = true;
}

if ($needf==true){	
	header("Location: http://".$_SERVER["HTTP_HOST"].CPATH."chat/".$_chat["name"]);
	die();
}

require("includes/functions_chat.inc.php");

require("includes/sajax.inc.php");

$sajax_request_type = "POST";
$sajax_remote_uri = $_SERVER["REQUEST_URI"];
sajax_init();
sajax_export("insert_msg", "refresh","change_settings");
sajax_handle_client_request();

//verify chatroom name
if (!ctype_alnum($_chat["name"]) || strlen($_chat["name"])==0){
	header("Location: ".CPATH."?msg=1");
	die();
}else if (strlen($_chat["name"])>25){
	header("Location: ".CPATH."?msg=2");
	die();
}else{

	$q = "SELECT `id` FROM `".PREFIX."rooms` WHERE `roomname` = '".$_chat["name"]."'";
	$r = mysql_query($q) or die(mysql_error());

	if (mysql_num_rows($r)==0){ // check and create row in table if needed
		$q = "INSERT INTO `".PREFIX."rooms` (`roomname` , `updated` ,`lines`)";
		$q .= "VALUES('".$_chat["name"]."','0','0')";	
		$result = mysql_query($q) or die(mysql_error());
	}
}

$_SESSION["count"]++;

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
       "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html>


<head>
	<title>ajchat - #<?= $_chat["name"]; ?></title>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="keywords" content="ajchat, chat, <?= $_chat["name"]; ?>">
	<link rel="Stylesheet" href="<?= CPATH; ?>screen.css" type="text/css" media="screen" />
	<link rel="Stylesheet" href="<?= CPATH; ?>bbcode.css" type="text/css" media="screen" />
	<link rel="Stylesheet" href="<?= CPATH; ?>viewstyle.css" type="text/css" media="screen" />
	

<script language="JavaScript" type="text/javascript" src="<?= CPATH; ?>js/dojo/dojo.js"></script>
<script language="JavaScript" type="text/javascript">
	dojo.require("dojo.xml.*");
	dojo.require("dojo.graphics.*");
</script>

<script type="text/javascript" language="JavaScript">
	<? sajax_show_javascript(); ?>
	
	var max_lines = <?= $_SESSION["maxlines"]; ?>;
	var dateformat = <?= $_SESSION["dateformat"]; ?>;
	var timezone = <?= $_SESSION["timezone"]; ?>;
	var displayname = "<?= $_SESSION["displayname"]; ?>";
	var chatname = "<?= $_chat["name"]; ?>";
	var uname = <?= $_SESSION["count"]; ?>;
	var path = "<?= CPATH; ?>";
	var msgid = 0;
	
</script>
<script type="text/javascript" src="<?= CPATH; ?>js/chat.js"></script>

</head>

<body onLoad="loadPage();">

<? include("includes/header.inc.php"); ?>

<div id="contents">

		<div class="box_c">
			<h1>#<?= $_chat["name"]; ?></h1>

			<form onSubmit="insert();return false;" action="#">

			<input type="text" name="handle" id="handle" value="<?= $_SESSION["displayname"]; ?>" onFocus="this.select();" onBlur="checkHandle();" style="width:130px;" class="databox" />
			<br/>
			<input type="text" name="message" id="message" value="(enter your message here)" onFocus="checkMsg();" style="width:450px;" class="databox" />
			
			<input type="submit" name="check" value="Send" onclick="insert(); return false;" class="button" />
			
			<input type="button" id="opt_button" name="opt_button" value="Options >>" onClick="toggleDisplay('opts');" class="button"/>
			
			<div id="opts">

			<div id="optsi">
		
				<b>[Settings]</b>
			
				&bull; 
				Refresh Max Lines: 
				<select name="maxl" id="maxl" onChange="changeSettings();">
					<?
						for ($i=0;$i<count($_c["chatlines"]);$i++){
							print "\t\t\t\t\t<option value=\"".$_c["chatlines"][$i]."\"";
							if ($_SESSION["maxlines"]==$_c["chatlines"][$i]) print " selected=\"selected\"";
							print ">".$_c["chatlines"][$i]."</option>\n";
						}
					?>		
				</select>
				
				&bull;
				Date Format: 
				<select name="datef" id="datef" onChange="changeSettings();" >
				
					<?
						$addtime = $_SESSION["timezone"]*3600;
						$time = time() + $addtime;
						$length = count($_c["dateformat"]);
						for ($i=0;$i<$length;$i++){
							print "\t\t\t\t\t<option value=\"$i\"";
							if ($_SESSION["dateformat"]==$i) print " selected=\"selected\"";
							if ($i==0) print ">".$_c["dateformat"][$i]."</option>\n";
							else print ">".gmdate($_c["dateformat"][$i],$time)."</option>\n";
						}
					?>
				</select>
				&bull;
				Timezone:
				
				<select name="timezone" id="timezone" onChange="changeSettings();" >
					<?
						$length = count($_c["timezone"]);
						for ($i=0;$i<$length;$i++){
							print "\t\t\t\t\t<option value=\"".$_c["timezone"][$i]."\"";
							if ($_SESSION["timezone"]==$_c["timezone"][$i]) print " selected=\"selected\"";
							print ">GMT ";
							if ($_c["timezone"][$i]>0) print "+";
							print $_c["timezone"][$i]." Hours</option>\n";
						}
					
					?>
				</select>
			
			</div>
			
			<div id="optsi">
			
				<b>[Tools]</b>
				
				&bull; <a href="javascript:saveData();">Save Log</a>
				&bull; <a href="javascript:share();">Share Chat</a>
				&bull; <a href="javascript:link();">Link from Blog</a>
				&bull; <a href="javascript:clearData();">Clear Chat Data</a>
				&bull; 
				
				<select name="help" id="help" onChange="getHelp();" >
					<option value="help">Help</a>
					<option value="bbcode">BBCode</a>
					<option value="smilies">Smilies</a>
				</select>

				<div id="share" style="display: none;">
					<center>
					<br/>
					Simply copy and paste the code below into your webpage.<br/>
					<textarea readonly="readonly" rows="3" cols="65" onclick="this.focus();this.select()"><iframe name="ajchat" src="http://<? print $_SERVER["HTTP_HOST"].CPATH."schat/".$_chat["name"]; ?>" width="450px" height="400px" scrolling="yes" frameborder="0" style="border: 1px solid #4260BF;"></textarea>
					<br/>(<a href="javascript:share();">Close</a>)
					</center>
				</div>	
				
				<div id="link" style="display: none;">
					<center>
					<br/>
					Simply copy and paste the code below into your blog.<br/>
					<textarea readonly="readonly" rows="2" cols="65" onclick="this.focus();this.select()">ajchat: <a href="http://www.ajchat.com/chat/<?= $_chat["name"]; ?>" rel="tag"><?= $_chat["name"]; ?></a></textarea>
					<br/>(<a href="javascript:link();">Close</a>)
					</center>
				</div>		

			</div>

			</div>
			
			<br/><br/>
			
			<div id="chatbox"><b>Loading...</b></div>
			
			</form>

		</div>

</div>


<? include("includes/footer.inc.php"); ?>

</body>

</html>