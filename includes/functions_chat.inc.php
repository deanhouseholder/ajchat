<?


require("includes/functions_msg.inc.php");

function microtime_float(){
    list($usec, $sec) = explode(" ", microtime());
    return ((float)$usec + (float)$sec);
}

function checkEvent($event){
	global $_chat, $_c, $_SESSION;

	$time = microtime_float();
	$color = colorifyIP($_SERVER["REMOTE_ADDR"]);

	if ($event==1) $msg = "[".$_SESSION["displayname"]."] <i>has entered the conversation.</i>";
	else if ($even==2) $msg = "[".$_SESSION["displayname"]."] <i>has left the conversation.</i>";
	else if ($even==3) $msg = "";

	$f = fopen("chatdata/".$_chat["name"].".dat", "a");	
	fwrite($f, $color."<~>".$time."<~>"." "."<~>".$msg."\n");
	fclose($f);	
	
	$f = fopen("chatdata/".$_chat["name"].".activity", "a");	
	fwrite($f, $_SERVER["REMOTE_ADDR"]."|".$time."|".$_SESSION["username"]);
	fclose($f);	

}

function checkHandle($username){
	$q = "SELECT `id` FROM `".PREFIX."users` WHERE `username` = '".$username."' LIMIT 1";
	$r = mysql_query($q) or die(mysql_error());
	if (mysql_num_rows($r)>0) return true; //exist
	else return false; // does not exist
}


function colorifyIP($ip){
	$parts = explode(".", $ip);
	return sprintf("%02s", dechex($parts[1])).
		   sprintf("%02s", dechex($parts[2])).
		   sprintf("%02s", dechex($parts[3]));
}

function insert_msg($handle, $msg) {
	
	global $_chat, $_c;
	
	$chatname = $_chat["name"];
	$error = false;
	$need_check = true;
	$invalid_handle = false;
	$time = microtime_float();
	
	$msg = trim($msg);
	$handle = trim(strtolower(strip_tags(stripslashes($handle))));
	
	if ($_c["login"]==true){
		if ($_SESSION["username"]==$handle) $need_check = false;
	}
	
	$ori_handle = $handle;
	
	if ($need_check==true && $handle!="" && $handle!="guest"){
		$error = checkHandle($handle);
		if ($error==true) $handle = "guest";
		else $_SESSION["displayname"] = $handle;
	}

	if ($msg=="")return false;
	else if ($handle==""){
		$handle = "guest";
		$invalid_handle = true;
	}
	
	if (isset($_SESSION["lastmsg"][$chatname]))
		if ($msg!=$_SESSION["lastmsg"][$chatname]){
			$_SESSION["lastmsg"][$chatname] = $msg;
		}else return "Don't Spam!";
	
	
	$msg = str_replace("\'", "'",$msg);
	$msg = str_replace("\\\"", '"',$msg);
	$msg = wordFilter(bbcode(htmlentities($msg,ENT_QUOTES,"UTF-8")));
	$msg = autolink($msg);
		
	/*
		Format: 
		color<~>datetime<~>handle<~>msg\n
	*/
	
	$color = colorifyIP($_SERVER["REMOTE_ADDR"]);

	$f = fopen("chatdata/".$chatname.".dat", "a");	
	fwrite($f, $color."<~>".$time."<~>".$handle."<~>".$msg."\n");
	fclose($f);

	$q = "UPDATE `".PREFIX."rooms` SET `updated` = '".$time."' WHERE `roomname` = '".$chatname."'";
	$r = mysql_query($q) or die(mysql_error());
	
	if ($error==true) return "The name [".$ori_handle."] has been taken. Please login or use a different name.";
	else if ($invalid_handle==true) return "Please enter a valid username";
	else return "";
}



function refresh($force,$uname) {
	/*
		force type
		0: time + one line only 
		1: reload all
	*/
	
	global $_chat, $_c;
	
	$_chat["uname"] = $_chat["name"]."+".$uname;
	
	$chatname = $_chat["name"];
	$clines = $_SESSION["maxlines"];
	$lastrefresh = $_SESSION["lastupdated"][$_chat["uname"]];
	
	
	if ($_SESSION["needrefresh"][$_chat["uname"]]==1){
		$force = 1;
		$_SESSION["needrefresh"][$_chat["uname"]] = 0;
	}
	
	$q = "SELECT `updated` FROM `".PREFIX."rooms` WHERE `roomname` = '".$chatname."'";

	$r = mysql_query($q) or die(mysql_error());
	$row = mysql_fetch_assoc($r);
	
	if ($row["updated"]>$lastrefresh || ($force==1)){

		$time = microtime_float();
	
		$filepath = "chatdata/".$chatname.".dat";

		if (!file_exists($filepath)){
			$data[0] = "O";
		}else{
			$lines = file($filepath);

			if (count($lines)==0) $data[0] = "O";
			else{
				
				if (count($lines)>200){ //clear stuff
					
					//rewrite
					$rewrite = implode("",array_slice($lines, 100)); //only last 100 lines are re-saved
					$f = fopen("chatdata/".$chatname.".dat","w");
					fwrite($f,$rewrite);
					fclose($f);
					
					//write to datdump
					$datdump = implode("",array_slice($lines,0,100)); //write only first 100 lines
					$f = fopen("chatdata/".$chatname.".datdump","a");
					fwrite($f,$datdump);
					fclose($f);
					
					// update mysql count
					
					$q = "SELECT `lines` FROM `".PREFIX."rooms` WHERE `roomname` = '".$chatname."'";
					$r = mysql_query($q) or die(mysql_error());
					
					$l = mysql_fetch_assoc($r);
					
					$q = "UPDATE `".PREFIX."rooms` SET `lines` = '".($l["lines"]+100)."' WHERE `roomname` = '".$chatname."' LIMIT 1";
									
					$r = mysql_query($q) or die(mysql_error());
					
				}
				
				$addtime = $_SESSION["timezone"]*3600;
				
				if ($force==1){ //load everything
					$lines = array_reverse(array_slice($lines, -1 * $clines));
					$data[0] = "A";
					
					for ($i=0;$i<count($lines);$i++){				
						/*
							Format: 
							color<~>datetime<~>handle<~>msg\n
						*/
						$temp = explode("<~>",$lines[$i]);
						
						$data[1] .= $temp[0]."<~>";

						if ($_SESSION["dateformat"]!=0)
							$data[1] .= gmdate($_c["dateformat"][$_SESSION["dateformat"]],$temp[1]+$addtime)."<~>";
						else $data[1] .= " <~>";

						$data[1] .= $temp[2]."<~>".smilies($temp[3])."<~~>";
					}
				
				}else{ // load only new
					
					$data[0] = "S";
					
					for ($i=count($lines)-1;$i>=0;$i--){
						$temp = explode("<~>",$lines[$i]);
						
						if ($temp[1]>$lastrefresh){
							$data[1] .= $temp[0]."<~>";

							if ($_SESSION["dateformat"]!=0)
								$data[1] .= gmdate($_c["dateformat"][$_SESSION["dateformat"]],$temp[1]+$addtime)."<~>";
							else $data[1] .= " <~>";

							$data[1] .= $temp[2]."<~>".smilies($temp[3])."<~~>";
						}else break;
						
					}
				}
			}
		}
	}else{
		return "N";
	}
	
	/*
		data[0] = (N)ULL for no updates, (A)LL for everything, (S)OME for quick update, (O) for empty, (D) for debugging
		data[1] = messages
	*/
	
	$_SESSION["lastupdated"][$_chat["uname"]] = $time;
	
	return $data[0]."<~~>".$data[1];

}

function change_settings($clines,$dateformat,$timezone,$uname){
	global $_chat, $_c;
	
	$_chat["uname"] = $_chat["name"]."+".$uname;
	
	if ($clines>1 && $clines<=200 && is_numeric($clines) && is_numeric($dateformat) && is_numeric($timezone)){
		if ($_c["login"]==true){
			$q = "UPDATE `".PREFIX."users` SET `maxlines` = '".$clines."', `dateformat` = '".$dateformat."', `timezone` = '".$timezone."'";
			$q .= " WHERE `username` = '".$_SESSION["username"]."'";
			$r = mysql_query($q) or die(mysql_error());
		}
		
		$_SESSION["needrefresh"][$_chat["uname"]] = 1;
		$_SESSION["maxlines"] = $clines;
		$_SESSION["dateformat"] = $dateformat;
		$_SESSION["timezone"] = $timezone;
		
	}else return "Hacking attempt?";
}

?>