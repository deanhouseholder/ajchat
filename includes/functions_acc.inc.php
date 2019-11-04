<?

function validate_email($email) {
   if (strlen($email)<=3) return false;
   // Create the syntactical validation regular expression
	$regexp = "^([_a-z0-9-]+)(\.[_a-z0-9-]+)*@([a-z0-9-]+)(\.[a-z0-9-]+)*(\.[a-z]{2,4})$";

   // Presume that the email is invalid
   	$valid = false;

   // Validate the syntax
    if (eregi($regexp, $email))
    {
		list($username,$domaintld) = split("@",$email);
      	
      	// Validate the domain (does not work on Windows)
      	// Uncomment the following line if you are running Windows
      	if (getmxrr($domaintld,$mxrecords)) $valid = true;
      	
      	// Uncomment the following line if you are running Windows
      	//$valid = true;
      	
   	}else {
		return false;
   	}

   return $valid;
}

function validate_username($username){
	/*
	Errors
	1: Accepted
	2: Not alphanumeric
	3: Length unacceptable
	4: Username already exists.
	*/

	if (ctype_alnum($username)==false) return 2; 
	else if (strlen($username)<3 || strlen($username)>20) return 3;
		
	$q = "SELECT `password` FROM `".PREFIX."users` WHERE `username` = '".$username."'";
	
	$r = mysql_query($q);
	
	if (mysql_num_rows($r)>0) return 4;
	
	return 1;
}

function validate_password($password){
	if (strlen($password)<6) return false;
	else return true;
}

?>