/*
	Copyright (c) 2005-2006, ajchat.com
	All Rights Reserved.
	
	www.ajchat.com
	
*/

function saveData(){
	oNewWindow = window.open(path+"save.php?roomname="+chatname);
}

function clearData(){
	document.getElementById("chatbox").innerHTML = "";
}

function link(){
	if (document.getElementById("link").style.display=='none'){
		document.getElementById("link").style.display = 'block'
		document.getElementById("share").style.display = 'none';
	}else document.getElementById("link").style.display = 'none';
}

function share(){
	if (document.getElementById("share").style.display=='none'){
		document.getElementById("share").style.display = 'block'
		document.getElementById("link").style.display = 'none';
	}else document.getElementById("share").style.display = 'none';

}


function outputMsg(m_color,m_dt,m_name,m_msg){
	var outmsg = "";

	
	if (m_name=="<s>") outmsg += "<div class='status'>";
	else outmsg += "<div class='nmsg'>";
/*
	outmsg += "<div id='misc'><span style='color: #"+m_color+";'>"+m_dt;
	if (m_name=="<s>") outmsg += "</div> <div id='omsg'><b>"+m_msg+"</b></span></div><br/>\n";
	else outmsg += " <b>["+m_name+"]</b></span></div><div id='omsg'>"+m_msg+"</div><br/>\n";
*/
	
	outmsg += "<span style='color: #"+m_color+";'>"+m_dt;
	if (m_name=="<s>") outmsg += " <b>"+m_msg+"</b></span><br/>\n";
	else outmsg += " <b>["+m_name+"]</b></span> "+m_msg+"<br/>\n";
	

	outmsg += "</div>";
	
	return outmsg;
}

function refresh_chat(data) {
	

	dat = data.split("<~~>");
	var pmsg = "", tmp;

	if (dat[0]=="A"){	
	
		for (i=1;i<dat.length-1;i++){
			tmp = dat[i].split("<~>");
			pmsg += outputMsg(tmp[0],tmp[1],tmp[2],tmp[3]);
		}
	
		document.getElementById("chatbox").innerHTML = pmsg;
		
	}else if (dat[0]=="S"){
		for (i=1;i<dat.length-1;i++){
			tmp = dat[i].split("<~>");
			pmsg += outputMsg(tmp[0],tmp[1],tmp[2],tmp[3]);
		}
				
		pmsg = "<div id='msg"+msgid+"' style='display: block;'>"+pmsg+"</div>";
						
		document.getElementById("chatbox").innerHTML = pmsg + document.getElementById("chatbox").innerHTML;
		
		dojo.graphics.htmlEffects.colorFade(document.getElementById("msg"+msgid), [198,226,255], [255,255,255],200);
		
		msgid++;
	}else if (dat[0]=="O"){
		document.getElementById("chatbox").innerHTML = "There is currently no chat data.";
		setTimeout("refresh(1)", 1000);
		return false;
	}

	setTimeout("refresh(0)", 750);
}

function refresh(type) {
	x_refresh(type,uname,refresh_chat);
}

function err0(error_message){
	if (error_message!=""){
		alert(error_message);
		document.getElementById("handle").value = displayname;
	}
}

function err1(error_message){
	if (error_message!="") alert(error_message);
}


function insert() {
	var handle;
	var message;

	handle = document.getElementById("handle").value;
	message = document.getElementById("message").value;

	if (message=="" || message=="(enter your message here)"){
		return false;
	}else{
		if (handle=="(your name)") handle = "";
		if (handle==""){
			alert("Enter a name?"); 
			document.getElementById("handle").value = "(your name)";
			document.getElementById("handle").focus();
			return false;
		}
		x_insert_msg(handle,message,err0);
		document.getElementById("message").value = "";
		document.getElementById("message").focus();
	}

}

function checkHandle(){
	if (document.getElementById("handle").value=="")
		document.getElementById("handle").value = displayname;
}

function checkMsg(){
	if (document.getElementById("message").value=="(enter your message here)") document.getElementById("message").select();
}

function changeSettings(){
	max_lines = document.getElementById("maxl").value;
	dateformat = document.getElementById("datef").value;
	timezone = document.getElementById("timezone").value;
	document.getElementById("chatbox").innerHTML = "<b>Updating... hold on buddy...</b>";
	x_change_settings(max_lines,dateformat,timezone,uname,err1);
}

function toggleDisplay(id1) {
	if (document.getElementById(id1).style.display == 'none') {
		document.getElementById(id1).style.display = 'block';
		document.getElementById("opt_button").value="Hide Options";
	} else {
		document.getElementById(id1).style.display = 'none';
		document.getElementById("opt_button").value="Options >>";
	}
	return false;
}

function loadPage(){
	document.getElementById("opts").style.display = 'none';
	refresh(1);
}

function getHelp(){
	var topic = document.getElementById("help").value;
	
	if (topic!="help"){
		oNewWindow = window.open(path+"help/"+topic);
		document.getElementById("help").value = "help";
	}
	
	return false;
}
