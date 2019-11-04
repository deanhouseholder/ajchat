<?

$bbcodes = array(
	'/(?<!\\\\)\[color(?::\w+)?=(.*?)\](.*?)\[\/color(?::\w+)?\]/si'   => "<span style=\"color:\\1\">\\2</span>",
	'/(?<!\\\\)\[size(?::\w+)?=(.*?)\](.*?)\[\/size(?::\w+)?\]/si'     => "<span style=\"font-size:\\1\">\\2</span>",
	'/(?<!\\\\)\[font(?::\w+)?=(.*?)\](.*?)\[\/font(?::\w+)?\]/si'     => "<span style=\"font-family:\\1\">\\2</span>",
	'/(?<!\\\\)\[align(?::\w+)?=(.*?)\](.*?)\[\/align(?::\w+)?\]/si'   => "<div style=\"text-align:\\1\">\\2</div>",
	'/(?<!\\\\)\[b(?::\w+)?\](.*?)\[\/b(?::\w+)?\]/si'                 => "<span style=\"font-weight:bold\">\\1</span>",
	'/(?<!\\\\)\[i(?::\w+)?\](.*?)\[\/i(?::\w+)?\]/si'                 => "<span style=\"font-style:italic\">\\1</span>",
	'/(?<!\\\\)\[u(?::\w+)?\](.*?)\[\/u(?::\w+)?\]/si'                 => "<span style=\"text-decoration:underline\">\\1</span>",
	'/(?<!\\\\)\[center(?::\w+)?\](.*?)\[\/center(?::\w+)?\]/si'       => "<div style=\"text-align:center\">\\1</div>",

	// [email]
	'/(?<!\\\\)\[email(?::\w+)?\](.*?)\[\/email(?::\w+)?\]/si'         => "<a href=\"mailto:\\1\" class=\"bb-email\">\\1</a>",
	'/(?<!\\\\)\[email(?::\w+)?=(.*?)\](.*?)\[\/email(?::\w+)?\]/si'   => "<a href=\"mailto:\\1\" class=\"bb-email\">\\2</a>",

	// [url]
	'/(?<!\\\\)\[url(?::\w+)?\]www\.(.*?)\[\/url(?::\w+)?\]/si'        => "<a href=\"http://www.\\1\" target=\"_blank\" class=\"bb-url\">\\1</a>",
	'/(?<!\\\\)\[url(?::\w+)?\](.*?)\[\/url(?::\w+)?\]/si'             => "<a href=\"\\1\" target=\"_blank\" class=\"bb-url\">\\1</a>",
	'/(?<!\\\\)\[url(?::\w+)?=(.*?)?\](.*?)\[\/url(?::\w+)?\]/si'      => "<a href=\"\\1\" target=\"_blank\" class=\"bb-url\">\\2</a>",

	// [img]
	'/(?<!\\\\)\[img(?::\w+)?\](.*?)\[\/img(?::\w+)?\]/si'             => "<img src=\"\\1\" alt=\"\\1\" class=\"bb-image\" />",
	'/(?<!\\\\)\[img(?::\w+)?=(.*?)x(.*?)\](.*?)\[\/img(?::\w+)?\]/si' => "<img width=\"\\1\" height=\"\\2\" src=\"\\3\" alt=\"\\3\" class=\"bb-image\" />",

	// [quote]
	'/(?<!\\\\)\[quote(?::\w+)?\](.*?)\[\/quote(?::\w+)?\]/si'         => "<div class=\"bb-code-title\">QUOTE:<div class=\"bb-code\">\\1</div></div>",
	'/(?<!\\\\)\[quote(?::\w+)?=(?:&quot;|"|\')?(.*?)["\']?(?:&quot;|"|\')?\](.*?)\[\/quote\]/si'   => "<div class=\"bb-code-title\">QUOTE \\1:<div class=\"bb-code\">\\2</div></div>",

	// [list]
	'/(?<!\\\\)(?:\s*<br\s*\/?>\s*)?\[\*(?::\w+)?\](.*?)(?=(?:\s*<br\s*\/?>\s*)?\[\*|(?:\s*<br\s*\/?>\s*)?\[\/?list)/si' => "<li class=\"bb-listitem\">\\1</li>",
	'/(?<!\\\\)(?:\s*<br\s*\/?>\s*)?\[\/list(:(?!u|o)\w+)?\](?:<br\s*\/?>)?/si'    => "</ul>",
	'/(?<!\\\\)(?:\s*<br\s*\/?>\s*)?\[\/list:u(:\w+)?\](?:<br\s*\/?>)?/si'         => "</ul>",
	'/(?<!\\\\)(?:\s*<br\s*\/?>\s*)?\[\/list:o(:\w+)?\](?:<br\s*\/?>)?/si'         => "</ol>",
	'/(?<!\\\\)(?:\s*<br\s*\/?>\s*)?\[list(:(?!u|o)\w+)?\]\s*(?:<br\s*\/?>)?/si'   => "<ul class=\"bb-list-unordered\">",
	'/(?<!\\\\)(?:\s*<br\s*\/?>\s*)?\[list:u(:\w+)?\]\s*(?:<br\s*\/?>)?/si'        => "<ul class=\"bb-list-unordered\">",
	'/(?<!\\\\)(?:\s*<br\s*\/?>\s*)?\[list:o(:\w+)?\]\s*(?:<br\s*\/?>)?/si'        => "<ol class=\"bb-list-ordered\">",
	'/(?<!\\\\)(?:\s*<br\s*\/?>\s*)?\[list(?::o)?(:\w+)?=1\]\s*(?:<br\s*\/?>)?/si' => "<ol class=\"bb-list-ordered,bb-list-ordered-d\">",
	'/(?<!\\\\)(?:\s*<br\s*\/?>\s*)?\[list(?::o)?(:\w+)?=i\]\s*(?:<br\s*\/?>)?/s'  => "<ol class=\"bb-list-ordered,bb-list-ordered-lr\">",
	'/(?<!\\\\)(?:\s*<br\s*\/?>\s*)?\[list(?::o)?(:\w+)?=I\]\s*(?:<br\s*\/?>)?/s'  => "<ol class=\"bb-list-ordered,bb-list-ordered-ur\">",
	'/(?<!\\\\)(?:\s*<br\s*\/?>\s*)?\[list(?::o)?(:\w+)?=a\]\s*(?:<br\s*\/?>)?/s'  => "<ol class=\"bb-list-ordered,bb-list-ordered-la\">",
	'/(?<!\\\\)(?:\s*<br\s*\/?>\s*)?\[list(?::o)?(:\w+)?=A\]\s*(?:<br\s*\/?>)?/s'  => "<ol class=\"bb-list-ordered,bb-list-ordered-ua\">",

	// escaped tags like \[b], \[color], \[url], ...
	'/\\\\(\[\/?\w+(?::\w+)*\])/'                                      => "\\1"
);

$smi = array(
	":D" => "<img src=\"../images/smilies/yellow/biggrin.gif\" alt=\"Very Happy\" />",
	":-D" => "<img src=\"../images/smilies/yellow/biggrin.gif\" alt=\"Very Happy\" />",
	":grin:" => "<img src=\"../images/smilies/yellow/biggrin.gif\" alt=\"Very Happy\" />",
	":biggrin:" => "<img src=\"../images/smilies/yellow/biggrin.gif\" alt=\"Very Happy\" />",
	":)" => "<img src=\"../images/smilies/yellow/smile.gif\" alt=\"Smile\" />",
	":-)" => "<img src=\"../images/smilies/yellow/smile.gif\" alt=\"Smile\" />",
	":smile:" => "<img src=\"../images/smilies/yellow/smile.gif\" alt=\"Smile\" />",
	":(" => "<img src=\"../images/smilies/yellow/sad.gif\" alt=\"Sad\" />",
	":-(" => "<img src=\"../images/smilies/yellow/sad.gif\" alt=\"Sad\" />",
	":sad:" => "<img src=\"../images/smilies/yellow/sad.gif\" alt=\"Sad\" />",
	":oops:" => "<img src=\"../images/smilies/yellow/redface.gif\" alt=\"Embarassed\" />",
	":o" => "<img src=\"../images/smilies/yellow/surprised.gif\" alt=\"Surprised\" />",
	":-o" => "<img src=\"../images/smilies/yellow/surprised.gif\" alt=\"Surprised\" />",
	":eek:" => "<img src=\"../images/smilies/yellow/surprised.gif\" alt=\"Surprised\" />",
	":shock:" => "<img src=\"../images/smilies/yellow/shock.gif\" alt=\"Shock\" />",
	":???:" => "<img src=\"../images/smilies/yellow/confused.gif\" alt=\"Confused\" />",
	":?:" => "<img src=\"../images/smilies/yellow/question.gif\" alt=\"Question\" />",
	":?" => "<img src=\"../images/smilies/yellow/confused.gif\" alt=\"Confused\" />",
	":-?" => "<img src=\"../images/smilies/yellow/confused.gif\" alt=\"Confused\" />",
	"8)" => "<img src=\"../images/smilies/yellow/cool.gif\" alt=\"Cool\" />",
	"8-)" => "<img src=\"../images/smilies/yellow/cool.gif\" alt=\"Cool\" />",
	":cool:" => "<img src=\"../images/smilies/yellow/cool.gif\" alt=\"Cool\" />",
	":lol:" => "<img src=\"../images/smilies/yellow/lol.gif\" alt=\"Laughing\" />",
	":x" => "<img src=\"../images/smilies/yellow/mad.gif\" alt=\"Mad\" />",
	":-X" => "<img src=\"../images/smilies/yellow/mad.gif\" alt=\"Mad\" />",
	":mad:" => "<img src=\"../images/smilies/yellow/mad.gif\" alt=\"Mad\" />",
	":p" => "<img src=\"../images/smilies/yellow/razz.gif\" alt=\"Razz\" />",
	":-p" => "<img src=\"../images/smilies/yellow/razz.gif\" alt=\"Razz\" />",
	":razz:" => "<img src=\"../images/smilies/yellow/razz.gif\" alt=\"Razz\" />",
	":cry:" => "<img src=\"../images/smilies/yellow/cry.gif\" alt=\"Crying or Very sad\" />",
	":evil:" => "<img src=\"../images/smilies/yellow/evil.gif\" alt=\"Evil or Very Mad\" />",
	":badgrin:" => "<img src=\"../images/smilies/yellow/badgrin.gif\" alt=\"Bad Grin\" />",
	":roll:" => "<img src=\"../images/smilies/yellow/rolleyes.gif\" alt=\"Rolling Eyes\" />",
	";)" => "<img src=\"../images/smilies/yellow/wink.gif\" alt=\"Wink\" />",
	";-)" => "<img src=\"../images/smilies/yellow/wink.gif\" alt=\"Wink\" />",
	":wink:" => "<img src=\"../images/smilies/yellow/wink.gif\" alt=\"Wink\" />",
	":!:" => "<img src=\"../images/smilies/yellow/exclaim.gif\" alt=\"Exclamation\" />",
	":idea:" => "<img src=\"../images/smilies/yellow/idea.gif\" alt=\"Idea\" />",
	":arrow:" => "<img src=\"../images/smilies/yellow/arrow.gif\" alt=\"Arrow\" />",
	":|" => "<img src=\"../images/smilies/yellow/neutral.gif\" alt=\"Neutral\" />",
	":-|" => "<img src=\"../images/smilies/yellow/neutral.gif\" alt=\"Neutral\" />",
	":neutral:" => "<img src=\"../images/smilies/yellow/neutral.gif\" alt=\"Neutral\" />",
	":doubt:" => "<img src=\"../images/smilies/yellow/doubt.gif\" alt=\"Doubt\" />"
);

function bbcode($sentence){
	/*
		Taken from Serendipity 0.8.5 [www.s9y.org]
		File: /serendipity/plugins/serendipity_event_bbcode/serendipity_event_bbcode.php
	
	*/
	
	global $bbcodes;
	
	$sentence = preg_replace(array_keys($bbcodes), array_values($bbcodes), $sentence);
	return $sentence;
}

function wordFilter($sentence){
	$filter = array(
		"fuck" => "f***",
		"FUCK" => "F***",
		"shit" => "sh**",
		"bastard" => "bast***",
		"[br]" => "<br/>" 	// [br]
	);
	
	return str_replace(array_keys($filter),array_values($filter),$sentence);
}

function smilies($sentence){
	global $smi;
	return str_replace(array_keys($smi),array_values($smi),$sentence);
}

function autolink($message){
    $text = " " . $message;
    $text = preg_replace("#([\n ])([a-z]+?)://([a-z0-9\-\.,\?!%\*_\#:;~\\&$@\/=\+]+)#i", "\\1<a href=\"\\2://\\3\" target=\"_blank\">\\2://\\3</a>", $text);
    $text = preg_replace("#([\n ])www\.([a-z0-9\-]+)\.([a-z0-9\-.\~]+)((?:/[a-z0-9\-\.,\?!%\*_\#:;~\\&$@\/=\+]*)?)#i", "\\1<a class='postlink' href=\"http://www.\\2.\\3\\4\" target=\"_blank\">www.\\2.\\3\\4</a>", $text);
    $text = preg_replace("#([\n ])([a-z0-9\-_.]+?)@([\w\-]+\.([\w\-\.]+\.)?[\w]+)#i", "\\1<a href=\"mailto:\\2@\\3\">\\2@\\3</a>", $text);
    $text = substr($text, 1);
    return($text);
}


$_c["dateformat"] = array(
	"Hide",
	"H:i:s",
	"Y-m-d g:i:s",
	"Y-m-d g:i:s a",
	"D j, Y, g:i a",
	"D M j G:i:s Y",
	"g:i:s a"
);

$_c["chatlines"] = array(10,25,50,75,100);

$_c["timezone"] = array(-12,-11,-10,-9,-8,-7,-6,-5,-4,-3.5,-3,-2,-1,0,1,2,3,4,5,5.5,6,6.5,7,8,9,9.5,10,11,12,13);

?>