<?php

class BbcodeParser
{
	private static $bbcode = array(
		'@\n@', // enter
		'#\[b\](.*?)\[/b\]#si',
		'#\[i\](.*?)\[/i\]#si',
		'#\[u\](.*?)\[/u\]#si',
		'#\[s\](.*?)\[/s\]#si',
		'#\[center\](.*?)\[/center\]#si',
		'#\[right\](.*?)\[/right\]#si',
		'#\[p\](.*?)\[/p\]#si',
		'#\[p indent=(.*?)\](.*?)\[/p\]#si',
		'#\[img\](.*?)\[/img\]#si',
		'#\[url\](.*?)\[/url\]#si',
		'#\[url=(.*?)\](.*?)\[/url\]#si',
		'#\[quote\](.*?)\[/quote\]#si',
		'#\[quote=(.*?)\](.*?)\[/quote\]#si',
		'#\[code\](.*?)\[/code\]#si',
		'#\[color=(.*?)\](.*?)\[/color\]#si',
		'#\[size=([0-9]{1,2})\](.*?)\[/size\]#si',
		'#\[jutsu\](.*?)\[/jutsu\]#si',
		'#\[jutsu=katon\](.*?)\[/jutsu\]#si',
		'#\[jutsu=doton\](.*?)\[/jutsu\]#si',
		'#\[jutsu=futon\](.*?)\[/jutsu\]#si',
		'#\[jutsu=suiton\](.*?)\[/jutsu\]#si',
		'#\[jutsu=raiton\](.*?)\[/jutsu\]#si',
		'#\[list\](.*?)\[/list\]#si',
		'#\[listo\](.*?)\[/listo\]#si',
		'#\[li\](.*?)\[/li\]#si',
		'#\[youtube\](.*?)\[/youtube\]#si',
		'#\[table\](.*?)\[/table\]#si',
		'#\[caption\](.*?)\[/caption\]#si', 
		'#\[tr\](.*?)\[/tr\]#si', 
		'#\[td\](.*?)\[/td\]#si',
		'#\[td=header\](.*?)\[/td\]#si',
		'#\[td colspan=(.*?)\](.*?)\[/td\]#si',
		'#\[td rowspan=(.*?)\](.*?)\[/td\]#si'
	);

	private static $html = array(
		'<br>',
		'<span class="strong">\\1</span>',
		'<span class="italic">\\1</span>',
		'<ins>\\1</ins>',
		'<del>\\1</del>',
		'<p class="center">\\1</p>',
		'<p class="right">\\1</p>',
		'<p class="paragraph">\\1</p>',
		'<p class="paragraph" style="text-indent: \\1px">\\2</p>',
		'<img src="\\1">',
		'<a href="\\1">[link]</a>',
		'<a href="\\1">\\2</a>',
		'<div><blockquote class="cytat">\\1</blockquote></div>',
		'<blockquote><cite><i class="icon-quote-right blockquote-cite"></i>\\1 napisał:</cite>\\2</blockquote>',
		'<pre>\\1</pre>',
		'<span style="color: \\1">\\2</span>',
		'<span style="font-size: \\1px">\\2</span>',
		'<span class="jutsu">\\1</span>',
		'<span class="jutsu katon">[Katon] \\1</span>',
		'<span class="jutsu doton">[Doton] \\1</span>',
		'<span class="jutsu futon">[Fūton] \\1</span>',
		'<span class="jutsu suiton">[Suiton] \\1</span>',
		'<span class="jutsu raiton">[Raiton] \\1</span>',
		'<ul>\\1</ul>',
		'<ol>\\1</ol>',
		'<li>\\1</li>',
		'<iframe width="560" height="315" src="\\1" frameborder="0" allow="autoplay; encrypted-media" allowfullscreen></iframe>',
		'<table>\\1</table>',
		'<caption>\\1</caption>',
		'<tr>\\1</tr>',
		'<td>\\1</td>',
		'<td class="table-header">\\1</td>',
		'<td colspan="\\1">\\2</td>',
		'<td rowspan="\\1">\\2</td>'
	);

	public static function get($tekst) {
		$tekst = htmlentities($tekst, ENT_QUOTES, "UTF-8");
		$tekst = preg_replace(self::$bbcode, self::$html, $tekst);
		return $tekst;
	}
}