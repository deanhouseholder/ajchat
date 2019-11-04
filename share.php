<?

require("includes/config.inc.php");

$_chat["error"] = 0;

if (!isset($_GET["roomname"])) $_chat["error"] = 0;
else{
	$_chat["name"] = $_GET["roomname"];

	if (!ctype_alnum($_chat["name"])){
		$_chat["error"] = 1;
	}else if (strlen($_chat["name"])>25){
		$_chat["error"] = 2;
	}
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
       "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html>


<head>
	<title>ajchat</title>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<link rel="Stylesheet" href="<?= CPATH; ?>screen.css" type="text/css" media="screen" />
	

<script type="text/javascript" language="JavaScript">

</script>

</head>

<body>

<? include("includes/header.inc.php"); ?>

<div id="contents">

	<div class="shadow">
		<div class="box">
<?
	if ($_chat["error"]>0){
?>
			<h1>Error</h1>
			
			Oops... Invalid chatroom name.<br/>
			Please ensure your chatroom name is alphanumeric and less than 25 characters long.
			
			<form action="<?= $_SERVER["PHP_SELF"]; ?>" method="get">
			Enter the chatroom for which you want to get the code: 
			<br/>
			<input type="text" name="roomname" size="30" /> 
				<input type="Submit" value="Get Code!" class="button" />
			</form>
			
<?
	}else if (!isset($_GET["roomname"])){
?>
		<h1>Share Chat</h1>
		
		Do more with <?= $_c["ajchat"]; ?> than just talking! You can even insert a chatroom right into your website!
		</br>
	
			<form action="<?= $_SERVER["PHP_SELF"]; ?>" method="get">
			Enter the chatroom for which you want to get the code: 
			<br/>
			<table>
				<tr>
					<td>Roomname:</td>
					<td><input type="text" name="roomname" size="30" /></td>
					<td class="info">Alphanumeric please...</td>
				</tr>
				<tr>
					<td>Short Description:</td>
					<td><input type="text" name="des" size="30" /></td>
					<td class="info">Maybe a short notice to users?</td>
				</tr>
			
				<tr>
					<td>&nbsp;</td>
					<td align="center"><input type="Submit" value=" Get Code! " class="button" />
					</td>
					<td>&nbsp;</td>
				</tr>
			</table>
			</form>
<?	
	}else{
?>

			<?  if (isset($_GET["link"])) print "<h1>Link from Blog</h1>"; 
				else print "<h1>Share Chat [#".$_chat["name"]."]</h1>";
			?>
			
			
			<div <? if (isset($_GET["link"])) print "style='display: none';"; ?>>
			
			You can share this chatroom with your friends by adding it to your webpage.
			<br/>Simply copy and paste the relevent code below into your webpage.

			<h2>Add Chat Page</h2>
			<p>
			<b><a href="<?= CPATH; ?>about/features.php">See example</a></b>
			</p>
			<textarea readonly="readonly" rows="4" cols="60" onclick="this.focus();this.select()"><iframe name="ajchat" src="http://<? print $_SERVER["HTTP_HOST"].CPATH."schat/".$_chat["name"]; if (isset($_GET["des"]) && !empty($_GET["des"])) print "?des=".urlencode($_GET["des"]); ?>" width="450px" height="400px" scrolling="yes" frameborder="0" style="border: 1px solid #4260BF;"></iframe>
			</textarea>

			</div>
			<?
				if (isset($_GET["link"])) print "<h2>Add a Link</h2>";
				else print "<h2>OR Add a Link</h2>";
			?>
			
			<p>Link a chatroom from your blog just like the way you link <a href="http://www.technorati.com" target="_blank">Technorati</a> tags.</p>
			
			<b>Example:</b>
			<br/>ajchat: <a href="http://www.ajchat.com/chat/<?= $_chat["name"]; ?>" rel="tag"><?= $_chat["name"]; ?></a>
			
			<p>
			<textarea readonly="readonly" rows="1" cols="60" onclick="this.focus();this.select()">ajchat: <a href="http://www.ajchat.com/chat/<?= $_chat["name"]; ?>" rel="tag"><?= $_chat["name"]; ?></a></textarea>
			</p>
			
			
		
<?
	}
?>
			
		</div>
	</div>


</div>


<? include("includes/footer.inc.php"); ?>

</body>

</html>