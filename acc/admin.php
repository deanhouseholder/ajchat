<?

require("../includes/config.inc.php");

if ($_c["login"]!=true){
	header("Location: ".CPATH."acc/login.php");
	die();
}

require("../includes/functions_msg.inc.php");
require("../includes/functions_acc.inc.php");

$q = "SELECT `email` FROM `".PREFIX."users` WHERE `username` = '".$_SESSION["username"]."' LIMIT 1";
$r = mysql_query($q) or die(mysql_error());
$row = mysql_fetch_assoc($r);
$_chat["email"] = $row["email"];

unset($row);

function change($pass_old,$pass_new,$pass_verify,$email,$maxlines,$dateformat,$timezone){
	global $_c;
	global $_SESSION;
	global $_chat;
	
	$email = mysql_real_escape_string(trim($email));
	
	if ($_c["login"]!=true){
		return 0;
	}else if (!is_numeric($maxlines) || !is_numeric($dateformat) || !is_numeric($timezone)){
		return 0;
	}
	
	$q = "UPDATE `".PREFIX."users` SET `maxlines` = '".$maxlines."', `dateformat` = '".$dateformat."', `timezone` = '".$timezone."'";
	
	
	if (!empty($pass_old)){
		$pass_old = md5(strip_tags($pass_old).$_c["pass_hash"]);
		
		if ($pass_old!=$_SESSION["password"]){
			return 2;
		}
		
		if (validate_email($email)==false) return 5;
		$q .= ", `email` = '".$email."'";

		if (!empty($pass_new)){
			if (validate_password($pass_new)==false) return 3;
			else if (strcmp($pass_new,$pass_verify)!=0) return 4;
			
			$pass_new = md5(strip_tags($pass_new).$_c["pass_hash"]);
			
			$_SESSION["password"] = $pass_new;
			
			$q .= ", `password` = '".$pass_new."'";
		}
		
	}else{
		if ($_chat["email"]!=$email) return 6;
	}
	

	$q .= " WHERE `username` = '".$_SESSION["username"]."'";	

	$r = mysql_query($q) or die(mysql_error());

	$_SESSION["maxlines"] = $maxlines;
	$_SESSION["dateformat"] = $dateformat;
	$_SESSION["timezone"] = $timezone;
	
	return 1;
}


require("../includes/sajax.inc.php");

$sajax_request_type = "POST";
$sajax_remote_uri = $_SERVER["REQUEST_URI"];
sajax_init();
sajax_export("change");
sajax_handle_client_request();

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
       "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html>


<head>
	<title>ajchat - acc [admin]</title>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<link rel="Stylesheet" href="../screen.css" type="text/css" media="screen" />

<script type="text/javascript" src="<?= CPATH; ?>js/dojo/dojo.js"></script>
<script type="text/javascript">
	dojo.require("dojo.widget.Dialog");
</script>
<script type="text/javascript">
	var dlg;
	function init(e) {
		dlg = dojo.widget.manager.getWidgetsByType("Dialog")[0];
	}
	dojo.event.connect(dojo.hostenv, "loaded", window, "init");
</script>

<script type="text/javascript" language="JavaScript">
	<?
		sajax_show_javascript();
	?>
	
	var btn = "<br/><input type='button' class='button' value=' ok ' onClick='javascript:reveal();' />";
	var email_ori = "<?= $_chat["email"]; ?>";
	
	var errmsg = new Array();
	
	errmsg[0] = "Hacking attempt.";
	errmsg[1] = "Hooray! Settings saved!";
	errmsg[2] = "Oops... Wrong old password.";
	errmsg[3] = "Oops... Invalid new password.";
	errmsg[4] = "Oops... Passwords do not match.";
	errmsg[5] = "Oops... Invalid email address.";
	errmsg[6] = "Oops... You must confirm your current password if you wish to alter your email address.";
	
	function reveal(){
		dlg.hide();
		document.adminf.maxl.disabled = false;
		document.adminf.timez.disabled = false;
		document.adminf.datef.disabled = false;
		document.adminf.password_old.value = "";
		document.adminf.password_new.value = "";
		document.adminf.password_verify.value = "";
	}
	
	function err(msgn){
	
		if (msgn==1){
			document.getElementById("msg").innerHTML = "<b>Horray!</b> Your modifications have been saved successfully.<br/>Click <a href='<?= CPATH; ?>'>here</a> to <a href='<?= CPATH; ?>'>return to the home page.</a>";
			document.getElementById("DialogContent").innerHTML = errmsg[1]+btn;
			email_ori = document.adminf.email.value;
			dlg.show();
		}else if (msgn==0 || (msgn>=2 && msgn<=6) ){
			document.getElementById("msg").innerHTML = errmsg[msgn];
			document.getElementById("DialogContent").innerHTML = errmsg[msgn]+btn;
			if (msgn==6 || msgn==5) document.adminf.email.value = email_ori;
			dlg.show();
		}
		
		return false;
	}
	
	function process(){
		var password_old = document.adminf.password_old.value;
		var password_new = document.adminf.password_new.value;
		var password_verify = document.adminf.password_verify.value;
		var email = document.adminf.email.value;
		var maxlines = document.adminf.maxl.value;
		var timezone = document.adminf.timez.value;
		var dateformat = document.adminf.datef.value;
	
		document.adminf.maxl.disabled = true;
		document.adminf.timez.disabled = true;
		document.adminf.datef.disabled = true;
		
		x_change(password_old,password_new,password_verify,email,maxlines,dateformat,timezone,err);
		document.getElementById("DialogContent").innerHTML = "Prcocessing information.<br/>Hold on buddy...";
		dlg.show();
		
		return false;
	}

</script>

</head>

<body>

<? include("../includes/header.inc.php"); ?>

<div id="contents">

	<div class="shadow">
		<div class="box">
			<h1>Account Admin for [<?= $_SESSION["username"]; ?>]</h1>
			
			<div class="msg" id="msg">Tweak your settings using this page!</div>
			
			<form action="#" name="adminf" method="post" onSubmit="process();return false;">
			
			<input type="hidden" name="got_submit" value="1" />
			
			<h2>Registration Information</h2>
			<table cellspacing="0" cellpadding="3">

			<tr>
				<td style="width: 150px;" align="right">Old Password:</td>
				<td><input type="password" name="password_old" maxlength="20" size="20" value="" /></td>
				<td class="info">You must confirm your current password if you wish to change it or alter your email address.</td>
			</tr>
			<tr>
				<td align="right">New Password:</td>
				<td><input type="password" name="password_new" maxlength="20" size="20" value="" /></td>
				<td class="info">You only need to supply a password if you want to change it.</td>
			</tr>
			<tr>
				<td align="right">Retype Password: </td>
				<td><input type="password" name="password_verify" maxlength="20" size="20" value="" /></td>
				<td class="info">You only need to confirm your password if you changed it above.</td>
			</tr>
			<tr>
				<td align="right">Email: </td>
				<td><input type="text" name="email" maxlength="90" size="20" value="<?= $_chat["email"]; ?>"/></td>
				<td class="info">You must confirm your current password if you wish to alter your email address.</td>
			</tr>
			
			</table>
			
			<h2>Settings</h2>
			
			<table cellspacing="0" cellpadding="3">

			<tr>
				<td style="width: 150px;" align="right">Refresh Max Lines:</td>
				<td>
					<select size="1" name="maxl" id="maxl">
						<?
							for ($i=0;$i<count($_c["chatlines"]);$i++){
								print "\t\t\t\t\t<option value=\"".$_c["chatlines"][$i]."\"";
								if ($_SESSION["maxlines"]==$_c["chatlines"][$i]) print " selected=\"selected\"";
								print ">".$_c["chatlines"][$i]."</option>\n";
							}
						?>		
					</select>
				</td>
			</tr>
			<tr>
				<td align="right">Date Format:</td>
				<td>
					<select size="1" name="datef" id="datef">

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
				</td>
			</tr>
			<tr>
				<td align="right">Timezone:</td>
				<td>
					<select name="timez" id="timez">
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
				</td>
			</tr>
			
			</table>
			<br/>
			
			<center>
				<input type="submit" value="Modify" class="button"/> 
				<input type="reset" value="Reset" class="button" style="font-weight: normal;" />
			</center>
			
			</form>
		</div>

	</div>

</div>


<div dojoType="dialog" id="DialogContent" bgColor="white" bgOpacity="0.5"></div>

<? include("../includes/footer.inc.php"); ?>

</body>

</html>