<h1>BBCode<a name="bbcode"></a></h1>

<p>					
<?= $_c[ajchat]; ?>  supports BBCode.
BBCode is a special implementation of HTML.  BBCode itself is similar in style to HTML: tags are enclosed in square braces [ and ] rather than &lt; and &gt; and it offers greater control over what and how something is displayed.
</p>

<p>
For a list of valid BBCode, please see the list below:
</p>

<ul>
	<li>[color]</li>
	<li>[size]</li>
	<li>[font]</li>
	<!--
	<li>[center]</li>
	<li>[align]</li>
	-->
	<li>[b]</li>
	<li>[i]</li>
	<li>[u]</li>
	<li>[email]</li>
	<li>[url]</li>
	<li>[img]</li>
	<li>[quote]</li>
	<li>[list]</li>
	<li>[br] (breakline, no end tag is needed for this)</li>

</ul>


<p>For example, typing "[b]Hello world![/b]" will result in <?= $_c[ajchat]; ?> displaying this:<br/>
<b>Hello world!</b>
<br/>in the conversation window. Have fun <b>e<i>x</i></b><u><i>p</i>e</u><b>r<i>i</i></b><u><i>m</i></u><b><u>e</u>n</b>t<u>i</u><i>n<b>g</b>!</i></p>

<p>For a more comprehensive guide on how to use BBCode, please visit <a href="http://www.phpbb.com/phpBB/faq.php?mode=bbcode" target="_blank">phpBB BBCode Guide</a>.
</p>