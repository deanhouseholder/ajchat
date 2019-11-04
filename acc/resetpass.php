<?

require("../includes/config.inc.php");

require("../includes/functions_acc.inc.php");

function resetpass($username,$email){
	
	global $_c;

	$username = mysql_escape_string(trim($username));
	$email = mysql_escape_string(trim($email));

	if (empty($username) || empty($email)) return 2;

	$q = "SELECT `id` FROM `".PREFIX."users` WHERE `username` = '".$username."' AND `email` = '".$email."' LIMIT 1";

	$r = mysql_query($q) or die(mysql_error());

	if (mysql_num_rows($r)<=0) return 2;

	$newpassword = substr(md5(time().$_c["pass_hash"]),0,20);
	
	$hashpassword = md5(strip_tags($newpassword).$_c["pass_hash"]);
	
	$q = "UPDATE `".PREFIX."users` SET `password` = '".$hashpassword."' WHERE `username` = '".$username."' LIMIT 1";
	
	$r = mysql_query($q) or die(mysql_error());
	
	$subject = "Your New Password";

	$mailheaders = "MIME-Version: 1.0\r\n";
	$mailheaders = "Content-Type: text/plain; charset=iso-8859-1\r\n";
	
	$mailheaders .= "From: no-reply@ajchat.com\r\n";
	
	$message = "Hi,\r\n\r\n";
	
	$message .= "Your password has been reset as you requested.\r\n";
	$message .= "You can change your password after login.\r\n";
	$message .= "New password: ".$newpassword."\r\n\r\n";
	
	$message .= "Thanks,\r\n";
	$message .= "www.ajchat.com\r\n\r\n\r\n";
	
	$message .= "--------------------\r\n";
	$message .= "If you believe you have received this email by mistake, pls contact support@ajchat.com";
	
	mail($email, $subject, $message, $mailheaders);

	return 1;
}



require("../includes/sajax.inc.php");

$sajax_request_type = "POST";
$sajax_remote_uri = $_SERVER["REQUEST_URI"];
sajax_init();
sajax_export("resetpass");
sajax_handle_client_request();

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
       "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html>


<head>
	<title>ajchat - acc [reset password]</title>
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
	
	var errmsg = new Array();
	
	errmsg[0] = "Hacking attempt.";
	errmsg[1] = "Hooray!  Password reset. Please check your email.";
	errmsg[2] = "Oops... You appear to have entered an invalid username or email address.";
	
	function reveal(){
		dlg.hide();
		document.adminf.email.value = "";
		document.adminf.username.value = "";
	}

	
	function err(msgn){

		if (msgn==1){
			document.getElementById("msg").innerHTML = "<b>Horray!</b> Password reset. Please check your email.</a>";
			document.getElementById("DialogContent").innerHTML = errmsg[1]+btn;
			dlg.show();


		}else if (msgn==0){
			document.getElementById("msg").innerHTML = errmsg[0];
			document.getElementById("DialogContent").innerHTML = errmsg[0]+btn;
			dlg.show();
		}else if (msgn==2){
			document.getElementById("msg").innerHTML = errmsg[2];
			document.getElementById("DialogContent").innerHTML = errmsg[2]+btn;
			dlg.show();
		}
		
		return false;
	}
	
	function process(){
		var email = document.adminf.email.value;
		var username = document.adminf.username.value;
		x_resetpass(username,email,err);
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
			<h1>Have you forgotten your password?</h1>
			
			Use this page only if you wish to reset your password. Your new password will then be emailed to you. After which, you can login and change your password.
			<br/><br/>
			<div id="msg" class="msg">What is your username and email address?</div>
			
			<form action="#" name="adminf" method="post" onSubmit="process();return false;">
			
			<table cellspacing="0" cellpadding="3">

			<tr>
				<td style="width: 150px;" align="right">Username: </td>
				<td><input type="text" name="username" maxlength="20" size="30" value="" />
			<tr>
				<td align="right">Email: </td>
				<td><input type="text" name="email" maxlength="90" size="30" value="" /></td>
				<td><span class="info">Enter the email address you submitted during registration..</span></td>
			</tr>
			<tr>
				<td>&nbsp;</td>
				<td><input type="submit" value="Reset Password" class="button" /></td>
			
			</tr>
			
			</table>
			
			</form>

		</div>

	</div>


</div>

<div dojoType="dialog" id="DialogContent" bgColor="white" bgOpacity="0.5"></div>


<? include("../includes/footer.inc.php"); ?>

</body>

</html>