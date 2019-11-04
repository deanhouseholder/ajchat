<div id="header">
	<table cellspacing="0" class="header"> 
		<tr>
			<td width="50%" valign="top">
				<a href="<?= CPATH; ?>">
					<img src="<?= CPATH; ?>images/ajchat.gif" height="46px" width="127px" alt="" />
				</a>
			</td>
			<td width="50%" class="bar">
				<form action="<?= CPATH; ?>" method="get">
					Chat: <input type="text" name="roomname2" size="20" maxlength="25" /> | <a href="<?= CPATH; ?>explore.php">Explore</a> |
					
					<?
						if ($_c["login"]==true){
							print "<a href=\"".CPATH."acc/admin.php\">Account</a> | ";
							print "<a href=\"".CPATH."acc/logout.php\">Log Out</a>";						
						}else{
							if (!isset($_GET["redirect"])) print "<a href=\"".CPATH."acc/login.php?redirect=".$_SERVER["REQUEST_URI"]."\">Log In</a> | <a href=\"".CPATH."acc/login.php?signup=1&amp;redirect=".$_SERVER["REQUEST_URI"]."\">Sign Up</a>";
							else print "<a href=\"".CPATH."acc/login.php?redirect=".$_GET["redirect"]."\">Log In</a> | <a href=\"".CPATH."acc/login.php?signup=1&amp;redirect=".$_GET["redirect"]."\">Sign Up</a>";
						}
					?>
				</form>
			</td>
		</tr>
	</table>
</div>