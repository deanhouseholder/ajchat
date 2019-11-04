<?

require("../includes/config.inc.php");

if (isset($_GET["redirect"])) $_chat["redirect"] = $_GET["redirect"];
else if (isset($_POST["redirect"])) $_chat["redirect"] = $_POST["redirect"];

if ($_c["login"]==true){
	header("Location: ".CPATH);
	die();
}

if (isset($_GET["signup"])){
	$_POST["got_submit"] = 2;
	$_POST["got_submit_new"] = true;
}

if (!empty($_POST)){
	if (!isset($_POST["got_submit"])){
		die("Hacking attempt.");
	}
	
	if (isset($_POST["username"]))
		$_POST["username"] = strtolower(strip_tags(mysql_real_escape_string(trim($_POST["username"]))));
	
	if ($_POST["got_submit"]==1){ //check_login
	
		$_POST["password"] = md5(strip_tags(trim($_POST["password"])).$_c["pass_hash"]);
	
		$q = "SELECT * FROM `".PREFIX."users` WHERE `username` = '".$_POST["username"]."'";
	
		$r = mysql_query($q) or die(mysql_error());
	
		$_chat["err"] = 0;
	
		if (mysql_num_rows($r)==0 || strlen($_POST["username"])==0) $_chat["err"] = 1;
		else{
			$row = mysql_fetch_assoc($r);
			if ($_POST["password"]!=$row["password"]) $_chat["err"] = 2;
		}	

		$_chat["err_msg"] = "";

		if ($_chat["err"]==1){
			$_chat["err_msg"] = "Your username could not be found.";
		}else if ($_chat["err"]==2){
			$_chat["err_msg"] = "Your password is incorrect.";
		}else{ // login!

			/*
			if (isset($_COOKIE[session_name()])) {
				setcookie(session_name(), '', time()-$_c["session_length"], '/');
			}
			*/			
			/*
			// Save needed data
			if (isset($_SESSION["lastupdated"])){
				reset($_SESSION["lastupdated"]);

				while (list($key, $value) = each($_SESSION["lastupdated"]) ) {
					$_c["needrefresh"][$key] = 1;
				}

			}
			$_c["count"] = $_SESSION["count"];
			//---------------
			*/

			//session_destroy();			
			session_regenerate_id(true);

			$_SESSION["username"] = $_POST["username"];
			$_SESSION["password"] = $_POST["password"];
			$_SESSION["userid"] = $row["id"];
			$_SESSION["time"] = time();
			$_SESSION["displayname"] = $_POST["username"];
			$_SESSION["dateformat"] = $row["dateformat"];
			$_SESSION["timezone"] = $row["timezone"];
			$_SESSION["maxlines"] = $row["maxlines"];
			
			$_SESSION["login"] = true;
			
			/*
			// Put needed data
			if (isset($_c["needrefresh"])){
				reset($_c["needrefresh"]);

				while (list($key, $value) = each($_c["needrefresh"]) ) {
					$_SESSION["needrefresh"][$key] = 1;
				}

			}

			$_SESSION["count"] = $_c["count"];
			//---------------
			*/
			
			if (isset($_POST["remember"])){
				setcookie("ajchat_sid",session_id(),time()+$_c["session_length"],"/");
			}else{
				setcookie("ajchat_sid","",time()-$_c["session_length"],"/");
			}
			
			session_write_close();

			if (isset($_chat["redirect"])) header("Location: ".$_chat["redirect"]);
			else header("Location: ".CPATH);
			die();
		}

	}else if ($_POST["got_submit"]==2 && !isset($_POST["got_submit_new"])){ // check register
		include("../includes/functions_acc.inc.php");
		
		// do some pruning
		$_POST["email"] = mysql_real_escape_string(trim($_POST["email"]));
	
		$username_result = validate_username($_POST["username"]);
		
		if ($username_result!=1){ 
			if ($username_result==2) $_chat["err_msg"] = "Username must be alphanumeric.";
			else if ($username_result==3) $_chat["err_msg"] = "Make sure your username is within 3 - 20 characters long.";
			else if ($username_result==4) $_chat["err_msg"] = "Please choose another usename. This username has been taken.";
			$_chat["err"] = 1;
			
		}else if (validate_email($_POST["email"])==false || empty($_POST["email"])){ 
			$_chat["err_msg"] = "Please enter a valid email address. ";
			$_chat["err"] = 4;
	
		}else if (validate_password($_POST["password"])==false){
			$_chat["err_msg"] = "Please enter a valid password.";
			$_chat["err"] = 2;
			
		}else if (strcmp($_POST["password"],$_POST["password_verify"])!=0){
			$_chat["err_msg"] = "Please make sure your passwords match each other.";
			$_chat["err"] = 3;
	
		}else{ //process information
			$_POST["password"] = md5($_POST["password"].$_c["pass_hash"]);
			
			$q =  "INSERT INTO `".PREFIX."users` (`username` , `password` , `email`,`maxlines`,`dateformat`,`timezone`)";
			$q .= "VALUES ('".$_POST["username"]."', '".$_POST["password"]."', '".$_POST["email"]."','25','1','0')";
			
			$r = mysql_query($q) or die(mysql_error());
			
			$_chat["registered"] = true;
			$_chat["err_msg"] = "You have been successfully registered. You can now login.";
			
			unset($_POST);
		}
	
	}else if (!isset($_POST["got_submit_new"])){
		die("Hacking attempt.");
	}


}


?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
       "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html>


<head>
	<title>ajchat - acc [login/signup]</title>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<link rel="Stylesheet" href="../screen.css" type="text/css" media="screen" />

<script type="text/javascript" language="JavaScript">
	function toggleDisplay(id1) {
		if (document.getElementById(id1).style.display == 'none') {
			document.getElementById(id1).style.display = 'block';
			document.getElementById("showsignup").style.display = 'none';	
		} else {
			document.getElementById(id1).style.display = 'none';
			document.getElementById("showsignup").style.display = 'block';
		}

		document.getElementById("shadow").className = 'shadow';
	}
</script>

</head>


<body>

<? include("../includes/header.inc.php"); ?>

<div id="contents">

	<div class="shadow" id="shadow">
		<div class="box">
			
			<? if ($_POST["got_submit"]!=2){ ?> 
			
			<div id="loginf">
			
			<h1>Log In</h1>
			
			<? if (!empty($_chat["err_msg"])) print "<div class=\"msg\">".$_chat["err_msg"]."</div>";?>

			<form action="<?= $_SERVER["PHP_SELF"]; ?>" method="post">
			
			<input type="hidden" name="got_submit" value="1" />
			
			<?
				if (isset($_chat["redirect"]))
				print "<input type=\"hidden\" name=\"redirect\" value=\"".$_chat["redirect"]."\" />";
			?>
			
			<table cellspacing="0" cellpadding="3">
				<tr<? if ($_chat["err"]==1) print " class=\"highlight\""; ?>>
					<td style="width: 130px;" align="right">Username: </td>
					<td><input type="text" name="username" maxlength="20" size="50" value="<? if (isset($_POST["username"])) print $_POST["username"]; ?>" /></td>
				</tr>
				<tr<? if ($_chat["err"]==2 || $_chat["err"]==3) print " class=\"highlight\""; ?>>
					<td align="right">Password: </td>
					<td><input type="password" name="password" maxlength="20" size="20" value="" />	
					 (<a href="resetpass.php">I forgot my password</a>)</td>
				</tr>
				<tr>
					<td>&nbsp;</td>
					<td><input type="checkbox" name="remember" id="rem" value="1" <? if (isset($_POST["remember"])) print "checked=\"checked\"";?> />
						<label for="rem">Remember me</label></td>
				</tr>
				<tr>
					<td>&nbsp;</td>
					<td><input type="submit" name="ac_login" value="Sign me in!" class="button" /></td>
				</tr>
			</table>
			
			</form>
			
			<br/>
			 
			<div id="showsignup">
				<h1><a href="javascript:toggleDisplay('signupf');">Sign Up >></a> <img src="../images/tango/contact-new.gif" alt="" /></h1>
				<a href="javascript:toggleDisplay('signupf');">Sign up</a> for a free account now to <a href="<?= CPATH; ?>help/whysignup">enjoy benefits</a>!
			</div>
			
			</div>
			
			<? } ?>
			
			<div id="signupf" <? if ($_POST["got_submit"]!=2) print "style=\"display: none;\""; ?>>
			
			<h1>Sign Up <img src="../images/tango/contact-new.gif" alt="" /></h1>
			
			<? if (!empty($_chat["err_msg"])) print "<div class=\"msg\">".$_chat["err_msg"]."</div>";?>
			
			<form action="<?= $_SERVER["PHP_SELF"]; ?>" method="post">
			
			<input type="hidden" name="got_submit" value="2" />
			
			<?
				if (isset($_chat["redirect"]))
				print "<input type=\"hidden\" name=\"redirect\" value=\"".$_chat["redirect"]."\" />";
			?>
			
			<table cellspacing="0" cellpadding="3">
				<tr<? if ($_chat["err"]==1 && $_POST["got_submit"]==2) print " class=\"highlight\""; ?>>
					<td style="width: 130px;" align="right">Username: </td>
					<td><input type="text" name="username" maxlength="20" size="25" value="<? if (isset($_POST["username"])) print $_POST["username"]; ?>" /></td>
					<td class="info">Username must be alphanumeric and within 3 - 20 characters long.</td>
				</tr>
				<tr<? if ($_chat["err"]==4 && $_POST["got_submit"]==2) print " class=\"highlight\""; ?>>
					<td align="right">Email:</td>
					<td><input type="text" name="email" maxlength="90" size="25" value="<? if (isset($_POST["email"])) print $_POST["email"]; ?>" /></td>
					<td class="info">Giving your email address allows a new password to be issued if you have forgotten it.</td>
				</tr>
				<tr<? if (($_chat["err"]==2 || $_chat["err"]==3)  && $_POST["got_submit"]==2) print " class=\"highlight\""; ?>>
					<td align="right">Password: </td>
					<td><input type="password" name="password" maxlength="20" size="20" value="" /></td>
					<td class="info">Must be at least 6 characters long.</td>
				</tr>
				<tr<? if ($_chat["err"]==3  && $_POST["got_submit"]==2) print " class=\"highlight\""; ?>>
					<td align="right">Retype password:</td>
					<td><input type="password" name="password_verify" maxlength="20" size="20" value="" /></td>
					<td>&nbsp;</td>
				</tr>

				<tr>
					<td colspan="3">&nbsp;</td>
				</tr>
				<tr>
					<td>&nbsp;</td>
					<td colspan="2"><input type="submit" name="ac_create_acc" value="Create account" class="button" />
					<? if ($_POST["got_submit"]!=2){ ?>(<a href="javascript:toggleDisplay('signupf');">or cancel</a>)<? } ?></td>
				</tr>

			</table>
			
			</form>
			
			</div>
			
		</div>

	</div>


</div>


<? include("../includes/footer.inc.php"); ?>

</body>

</html>