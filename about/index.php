<?

require("../includes/config.inc.php");

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
       "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html>


<head>
	<title>ajchat - about</title>
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
			
					<h1>About <img src="../images/tango/text-html.gif" alt="" /></h1>
					<ul>
						<li><a href="#idea">The idea</a></li>
						<li><a href="#why">Why use ajchat?</a></li>
						<li><a href="#feedback">Feedback/Bugs</a></li>
						<li><a href="#download">Download</a></li>
					</ul>

					<h2>The idea<a name="idea"></a></h2>

					<p><?= $_c["ajchat"]; ?> is a simple online chat system, done in a <a href="http://en.wikipedia.org/wiki/Web_2.0" target="_blank">web 2.0</a> style. Its so simple that we hope you like it!
					</p>
					
					<h2>Why use <?= $_c["ajchat"]; ?>?<a name="why"></a></h2>

					<ul>
						<li>When your chat program/network is down.</li>
						<li>When you need to meet up with your friends really quick! (and you don't have a chat program installed)</li>
						<li>When you desperately need an answer to a question (and you are tired of googling)</li>
						<li>When you feel like it!</li>
						<li>Anywhere, anytime, any browser! (Firefox, Flock, Internet Explorer, Safari)</li>
						<li>You don't need Java!</li>
					</ul>

					<h2>Feedback/Bugs<a name="feedback"></a></h2>

					<?= $_c["ajchat"]; ?> is still in its alpha stage. We encourage you to report any bugs to the <a href="http://sourceforge.net/tracker/?group_id=160100&atid=814490">SourceForge Bugs Tracker</a>, and any feature request to the <a href="http://sourceforge.net/tracker/?group_id=160100&atid=814493">SourceForge Feature Request Tracker</a>.</p>
					
					<p><b>Note:</b> The ajchat development team is <b>not responsible</b> for:</br>
					- what happens on this server<br/>
					- the content in the chatrooms<br/>
					- and how the owners of this website decides to implement <?= $_c["ajchat"]; ?>.</br>
					<br/>
					If you have problems regarding this website, you should email the owners of this website on which <?= $_c["ajchat"]; ?> is hosted on.
					</p>
					
					<h2>Download<a name="download"></a></h2>
					
					<?= $_c["ajchat"]; ?> is <a href="http://en.wikipedia.org/wiki/Open_source">open source</a>, released under the <a href="http://www.gnu.org/licenses/gpl.html">GNU General Public License</a>, and can be download from our <a href="http://sourceforge.net/projects/ajchat/">SourceForge Project Page</a>.
					
				</td>
			</tr>
		</table>
		
		</div>

	</div>


</div>


<? include("../includes/footer.inc.php"); ?>

</body>

</html>