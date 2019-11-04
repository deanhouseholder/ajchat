<?

require("../includes/config.inc.php");

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
       "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html>


<head>
	<title>ajchat - about [features]</title>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<link rel="Stylesheet" href="../screen.css" type="text/css" media="screen" />
</head>

<body>

<? include("../includes/header.inc.php"); ?>

<div id="contents">

	<div class="shadow">
		<div class="box">
		
		<table cellspacing="0">
			<tr>
				<td class="menu">		
					<? include("about_menu.inc.php"); ?>	
				</td>
				
				<td class="con">

					<h1>Features <img src="../images/tango/applications-graphics.gif" alt="" /></h1>
					
					<?= $_c["ajchat"]; ?> is <i>more</i> than an online chat system...
					
					<h2>Share chat</h2>
					You can add a chatroom to your webpage!<br/>
					Goto the chatroom you want, click "Options" and click "Share Chat" to get the code!<br/>
					Like the example below!
					
					<br/><br/>
					
					<iframe name="ajchat" src="http://<?= $_SERVER["HTTP_HOST"].CPATH."schat/technology"; ?>" width="450px" height="300px" scrolling="yes" frameborder="0" style="border: 1px solid #4260BF;"></iframe>
					
					<h2>Anywhere, anytime</h2>
					
					Chat away on your topic of interest anywhere and anytime. Afterall, we are <a href="http://www.mozilla.org" target="_blank">Firefox</a>, <a href="http://www.flock.com" target="_blank">Flock</a>, <a href="http://www.apple.com/safari/" target="_blank">Safari</a> and <a href="http://www.microsoft.com/windows/ie/" target="_blank">Internet Explorer 6</a> compatible!
					
					<p>
					<img src="<?= CPATH; ?>images/logo_firefox.jpg" width="101" height="100" alt="" /> 
					<img src="<?= CPATH; ?>images/logo_flock.jpg" width="125" height="100" alt="" />
					<img src="<?= CPATH; ?>images/logo_safari.jpg" width="88" height="100" alt="" />
					<img src="<?= CPATH; ?>images/logo_ie.jpg" width="97" height="100" alt="" />
					
					</p>
					
					<h2>BBCode</h2>
					
					You BBCode just like you use HTML. See <a href="<?= CPATH; ?>help/bbcode">help</a> for supported list of BBCode.
					
					<h2>Colorify usernames</h2>
					
					<?= $_c["ajchat"]; ?> colorifies usernames based on the user's IP address to help you differentiate the people you are talking to.
					
				
					<h2>Save chat log</h2>
					Saving your chat log (XML format) is just a click away.
					
					<p>
					<img src="<?= CPATH; ?>images/savelog.gif" alt="" width="208" height="78"/>
					</p>
					
				</td>
			</tr>
		</table>
		
		</div>

	</div>


</div>


<? include("../includes/footer.inc.php"); ?>

</body>

</html>