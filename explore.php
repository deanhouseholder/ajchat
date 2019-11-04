<?

require("includes/config.inc.php");

require("includes/sajax.inc.php");

function random(){
	$q = "SELECT `roomname` FROM `".PREFIX."rooms` WHERE `updated` > 0 ORDER BY RAND() LIMIT 1";
	$r = mysql_query($q) or die(mysql_error());
	
	$row = mysql_fetch_assoc($r);
	
	return $row["roomname"];
}

sajax_init();
sajax_export("random");
sajax_handle_client_request();

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
       "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html>


<head>
	<title>ajchat - explore</title>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<link rel="Stylesheet" href="<?= CPATH; ?>screen.css" type="text/css" media="screen" />

<script language="JavaScript" type="text/javascript" src="<?= CPATH; ?>js/dojo/dojo.js"></script>
<script language="JavaScript" type="text/javascript">
	dojo.require("dojo.xml.*");
	dojo.require("dojo.graphics.*");
</script>
<script type="text/javascript" language="JavaScript">
	<?
	sajax_show_javascript();
	?>
	
	function setRandom(roomname){
		
		var ran = "<a href='chat/"+roomname+"'>"+roomname+"</a>";
		
		dojo.graphics.htmlEffects.fadeHide(document.getElementById("random"), 800,function(node){
			node.innerHTML = ran;
			dojo.graphics.htmlEffects.fadeShow(document.getElementById("random"), 800);
			setTimeout("getRandom()",3500);
		}
		);
		
	}
	
	function getRandom(){
		x_random(setRandom);
		
	}
</script>

</head>

<body onload="getRandom();">

<? include("includes/header.inc.php"); ?>

<div id="contents">

	<div class="shadow">
		<div class="box">
		
			<h1>Explore</h1>
			
			We can only promise that it will be more <i>fun</i>...

			<p>			

			<table style="width: 100%;" cellspacing="0">
				<tr>
					<td width="50%" style="padding-right: 15px;">
				
						<h2>Share Chat</h2>
						Say <i>no</i> to tagboards. <br/>Say <i>yes</i> to instant 2 way communications.<br/>
						Add a chatroom to your blog or website now!

						<form action="share.php" method="get">
						<br/>
						<table cellspacing="0">
							<tr>
								<td>Roomname:</td>
								<td><input type="text" name="roomname" size="25" maxlength="25" /></td>
								<td class="info">Alphanumeric please...</td>
							</tr>
							<tr>
								<td>Description:</td>
								<td><input type="text" name="des" size="25" /></td>
								<td class="info">(Optional), <br/>BBCode allowed</td>
							</tr>

							<tr>
								<td>&nbsp;</td>
								<td align="center"><input type="Submit" value=" Get Code! " class="button" />
								</td>
								<td>&nbsp;</td>
							</tr>
						</table>
						</form>
			
			
						<h2>OR Add a Link</h2>
						
						<p>Link a chatroom from your blog just like the way you link <a href="http://www.technorati.com" target="_blank">Technorati</a> tags. Discuss topics of the day with <?= $_c["ajchat"]; ?>.</p>
						
						<form action="share.php" method="get">
						<input type="hidden" name="link" value="true">
						<table>
							<tr>
								<td>Roomname:</td>
								<td><input type="text" name="roomname" size="25" maxlength="25" /></td>
								<td class="info">Alphanumeric please...</td>
							</tr>

							<tr>
								<td>&nbsp;</td>
								<td align="center"><input type="Submit" value=" Get Code! " class="button" />
								</td>
								<td>&nbsp;</td>
							</tr>
						</table>
						</form>

					</td>
					<td style="border-left: 2px dashed #666666; padding-left: 15px;">
						<h2>Chatroom Directory</h2>
						
						<a href="directory.php" class="intro">Directory</a> (aka <?= $_c["ajchat"]; ?>'s yellow pages)
						
						<h2>Random</h2>
						Do you think your <a href="http://images.apple.com/itunes/home/images/ipodshuffle20050907.gif" target="_blank">life is random?</a><br/>
						How about getting into a random chatroom?
						<center>
						<div id="random" class="intro" style="width: 100px; background-color: white; text-align: center;"></div>
						</center>
					</td>
				
				</tr>
			
			
			</table>
			
			</p>

		</div>

	</div>


</div>


<? include("includes/footer.inc.php"); ?>

</body>

</html>