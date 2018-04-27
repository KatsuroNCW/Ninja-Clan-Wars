<?php

class HtmlParser
{
	private static $html = array(
		'#\<br>#si',
		'#\<span class\=\"strong\"\>(.*?)\</span\>#si',
		'#\<span class\=\"italic\"\>(.*?)\</span\>#si',
		'#\<ins\>(.*?)\</ins\>#si',
		'#\<del\>(.*?)\</del\>#si',
		'#\<p class\=\"p-center\"\>(.*?)\</p\>#si',
		'#\<p class\=\"p-right\"\>(.*?)\</p\>#si',
		'#\<p class\=\"paragraph\"\>(.*?)\</p\>#si',
		'#\<p class\=\"paragraph\"\ style\=\"text-indent\: (.*?)px\"\>(.*?)\</p\>#si',
		'#\<img src\=\"(.*?)\"\>#si',
		'#\<a href="(.*?)"\>(\[link\])\</a\>#si',
		'#\<a href="(.*?)"\>(.*?)\</a\>#si',
		'#\<div\>\<blockquote class\=\"cytat\"\>(.*?)\</blockquote\>\</div\>#si',
		'#\<blockquote\>\<cite\>\<i class\=\"icon-quote-right blockquote-cite\"\>\</i\>(.*?)napisał:\</cite\>(.*?)\</blockquote\>#si',
		'#\<pre\>(.*?)\</pre\>#si',
		'#\<span style\=\"color\: (.*?)\"\>(.*?)\</span\>#si',
		'#\<span style\=\"font-size\: ([0-9]{1,2})px\"\>(.*?)\</span\>#si',
		'#\<span class\=\"jutsu\"\>(.*?)\</span\>#si',
		'#\<span class\=\"jutsu katon\"\>\[Katon\] (.*?)\</span\>#si',
		'#\<span class\=\"jutsu doton\"\>\[Doton\] (.*?)\</span\>#si',
		'#\<span class\=\"jutsu futon\"\>\[Fūton\] (.*?)\</span\>#si',
		'#\<span class\=\"jutsu suiton\"\>\[Suiton\] (.*?)\</span\>#si',
		'#\<span class\=\"jutsu raiton\"\>\[Raiton\] (.*?)\</span\>#si',
		'#\<ul\>(.*?)\</ul\>#si',
		'#\<ol\>(.*?)\</ol\>#si',
		'#\<li\>(.*?)\</li\>#si',
		'#\<iframe width\=\"560\" height\=\"315\" src\=\"(.*?)\" frameborder\=\"0\" allow\=\"autoplay\; encrypted-media\" allowfullscreen\>\</iframe\>#si',
		'#\<table\>(.*?)\</table\>#si',
		'#\<caption\>(.*?)\</caption\>#si',
		'#\<tr\>(.*?)\</tr\>#si',
		'#\<td\>(.*?)\</td\>#si',
		'#\<td class\=\"table-header\"\>(.*?)\</td\>#si',
		'#\<td colspan\=\"(.*?)\"\>(.*?)\</td\>#si',
		'#\<td rowspan\=\"(.*?)\"\>(.*?)\</td\>#si'
	);

	private static $bbcode = array(
		'',
		'[b]$1[/b]',
		'[i]$1[/i]',
		'[u]$1[/u]',
		'[s]$1[/s]',
		'[center]$1[/center]',
		'[right]$1[/right]',
		'[p]$1[/p]',
		'[p indent=$1]$2[/p]',
		'[img]$1[/img]',
		'[url]$1[/url]',
		'[url=$1]$2[/url]',
		'[quote]$1[/quote]',
		'[quote=$1]$2[/quote]',
		'[code]$1[/code]',
		'[color=$1]$2[/color]',
		'[size=$1]$2[/size]',
		'[jutsu]$1[/jutsu]',
		'[jutsu=katon]$1[/jutsu]',
		'[jutsu=doton]$1[/jutsu]',
		'[jutsu=futon]$1[/jutsu]',
		'[jutsu=suiton]$1[/jutsu]', 
		'[jutsu=raiton]$1[/jutsu]',
		'[list]$1[/list]',
		'[listo]$1[/listo]',
		'[li]$1[/li]',
		'[youtube]$1[/youtube]',
		'[table]$1[/table]',
		'[caption]$1[/caption]', 
		'[tr]$1[/tr]', 
		'[td]$1[/td]',
		'[td=header]$1[/td]',
		'[td colspan=$1]$2[/td]',
		'[td rowspan=$1]$2[/td]'
	);

	public static function get($tekst) {
		$tekst = preg_replace(self::$html, self::$bbcode, $tekst);
		return $tekst;
	}
}