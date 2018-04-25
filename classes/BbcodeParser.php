<?php

class BbcodeParser {
	private static $bbcode = array(
		'@\n@', // enter
		'#\[b\](.*?)\[/b\]#si', // pogrubienie
		'#\[i\](.*?)\[/i\]#si', // kursywa
		'#\[u\](.*?)\[/u\]#si', // podkreślenie
		'#\[s\](.*?)\[/s\]#si', // przekreślenie
		'#\[center\](.*?)\[/center\]#si', // wyśrodkowanie
		'#\[right\](.*?)\[/right\]#si', // wyrównanie do prawej
		'#\[p\](.*?)\[/p\]#si', // akapit
		'#\[p indent=(.*?)\](.*?)\[/p\]#si', // akapit z okreslonym wcięciem
		'#\[img\](.*?)\[/img\]#si', // grafika
		'#\[url\](http.*?)\[/url\]#si', // link bez podpisu z http
		'#\[url=(http.*?)\](.*?)\[/url\]#si', // link z podpisem z http
		'#\[url\](.*?)\[/url\]#si', // link bez podpisu bez http
		'#\[url=(.*?)\](.*?)\[/url\]#si', // link z podpisem bez http
		'#\[quote\](.*?)\[/quote\]#si', // cytat bez autora
		'#\[quote=(.*?)\](.*?)\[/quote\]#si', // cytat z autorem
		'#\[code\](.*?)\[/code\]#si', // kod
		'#\[color=(.*?)\](.*?)\[/color\]#si', // kolor czcionki
		'#\[size=([0-9]{1,2})\](.*?)\[/size\]#si', // rozmiar czcionki
		'#\[jutsu\](.*?)\[/jutsu\]#si', // jutsu
		'#\[jutsu=katon\](.*?)\[/jutsu\]#si', // jutsu ognia
		'#\[jutsu=doton\](.*?)\[/jutsu\]#si', // jutsu ziemi
		'#\[jutsu=futon\](.*?)\[/jutsu\]#si', // jutsu wiatru
		'#\[jutsu=suiton\](.*?)\[/jutsu\]#si', // jutsu wody
		'#\[jutsu=raiton\](.*?)\[/jutsu\]#si', // jutsu błyskawicy
		'#\[list\](.*?)\[/list\]#si', // lista wypunktowana
		'#\[listo\](.*?)\[/listo\]#si', //lista numerowana
		'#\[li\](.*?)\[/li\]#si', // element listy
		'#\[youtube\](.*?)\[/youtube\]#si', // youtube

		'#\[table\](.*?)\[/table\]#si', // tabela
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
		'<p class="wysrodkowanie">\\1</p>',
		'<p class="do_prawej">\\1</p>',
		'<p class="akapit">\\1</p>',
		'<p class="akapit" style="text-indent: \\1px">\\2</p>',
		'<img src="\\1">',
		'<a href=\"\\1\">\\1</a>',
		'<a href=\"\\1\">\\2</a>',
		'<a href=\"http://\\1\">\\1</a>',
		'<a href=\"http://\\1\">\\2</a>',
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
		//$tekst = htmlspecialchars($tekst);
		$tekst = htmlentities($tekst, ENT_QUOTES, "UTF-8");
		//$tekst = nl2br($tekst);
		$tekst = preg_replace(self::$bbcode, self::$html, $tekst);
		return $tekst;
	}
}