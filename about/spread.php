<?

require("../includes/config.inc.php");

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
       "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html>


<head>
	<title>ajchat - about [spread ajchat]</title>
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

					<h1>Spread ajchat <img src="../images/tango/system-software-update.gif" alt="" /></h1>

					Do you love <?= $_c["ajchat"]; ?> and would like to spread it?
					<br/>You can add the following code on your site!

					<p>

					<b>Web Badge</b>  <img src="<?= CPATH; ?>images/ajchat_web.gif" width="80" height="15" alt="" />
					<br/>
					<br/>
					<textarea readonly="readonly" rows="3" cols="60" onclick="this.focus();this.select()"><? print "&lt;a href=&quot;http://".$_SERVER["HTTP_HOST"].CPATH."&quot;&gt;&lt;img src=&quot;http://".$_SERVER["HTTP_HOST"].CPATH."images/ajchat_web.gif&quot; width=&quot;80&quot; height=&quot;15&quot; alt=&quot;www.ajchat.com&quot; border=&quot;0&quot; &gt;&lt;/a&gt;"; ?></textarea>

					</p>

					<b>Logo</b>

					<p>
					<img src="<?= CPATH; ?>images/ajchat.gif" width="127" height="46" alt="" />

					<br/>
					<br/>
					<textarea readonly="readonly" rows="3" cols="60" onclick="this.focus();this.select()"><? print "&lt;a href=&quot;http://".$_SERVER["HTTP_HOST"].CPATH."&quot;&gt;&lt;img src=&quot;http://".$_SERVER["HTTP_HOST"].CPATH."images/ajchat.gif&quot; width=&quot;127&quot; height=&quot;46&quot; alt=&quot;www.ajchat.com&quot; border=&quot;0&quot; &gt;&lt;/a&gt;"; ?></textarea>			
					</p>
					<br/>

				</td>
			</tr>
		</table>
		
		</div>

	</div>


</div>


<? include("../includes/footer.inc.php"); ?>

</body>

</html>