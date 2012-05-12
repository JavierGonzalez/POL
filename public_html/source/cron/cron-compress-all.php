<?php
/* The source code packaged with this file is Free Software, Copyright (C) 2008 by
** Javier González González <desarrollo AT virtualpol.com> <gonzomail AT gmail.com>
** It's licensed under the GNU GENERAL PUBLIC LICENSE v3 unless stated otherwise.
** You can get copies of the licenses here: http://www.gnu.org/licenses/gpl.html
** The source: http://www.virtualpol.com/codigo - TOS: http://www.virtualpol.com/TOS
** VirtualPol, The first Democratic Social Network - http://www.virtualpol.com
*/


/* Este script unifica y comprime (minified) archivos js y css por optimización. */

$root_dir = str_replace('source/cron', '', dirname(__FILE__));
include_once($root_dir.'source/inc-functions.php');


function minify_css($t) {
	$t = preg_replace('#\s+#', ' ', $t);
	$t = preg_replace('#/\*.*?\*/#s', '', $t);
	$t = str_replace('; ', ';', $t);
	$t = str_replace(': ', ':', $t);
	$t = str_replace(' {', '{', $t);
	$t = str_replace('{ ', '{', $t);
	$t = str_replace(', ', ',', $t);
	$t = str_replace('} ', '}', $t);
	$t = str_replace(';}', '}', $t);
	$t = str_replace('  ', ' ', $t);
	return trim($t);
}

function minify_js($t) {
	//$t = preg_replace("/((?:\/\*(?:[^*]|(?:\*+[^*\/]))*\*+\/)|(?:\/\/.*))/", "", $t); /* elimina comentarios */
	//$t = str_replace(array("\r\n", "\r", "\t", "\n", '  ', '    ', '     '), '', $t); /* elimina tabulador, espacios, lineas nuevas, etc. */
	$t = preg_replace(array('(( )+\))','(\)( )+)'), ')', $t); /* elimina otros espacios antes y despues */
	return $t;
}

function compress_file($file, $tipo='js') {
	global $root_dir;
	$result = file_get_contents($root_dir.'img/'.$file);
	$len_antes = strlen($result);
	echo '<tr><td><b>'.strtoupper($tipo).'</b></td><td>'.$file.'</td><td align="right"><b>'.num($len_antes).'</b>bytes</td>';
	switch ($tipo) {
		case 'js': $result = minify_js($result);  break;
		case 'css': 
			$result = minify_css($result); 
			$result = str_replace('url(img/', 'url(lib/kickstart/css/img/', $result); /* corrige URLs de kickstart (codigo malo) */
			$result = str_replace('url(\'img/', 'url(\'lib/kickstart/css/img/', $result); /* corrige URLs de kickstart (codigo malo) */
			break;
	}
	$len_ahora = strlen($result);
	echo '<td align="right">- <b>'.num($len_antes-$len_ahora).'</b>bytes = </td><td align="right"><b>'.num($len_ahora).'</b>bytes</td><td align="right">'.num(100-(($len_ahora*100)/$len_antes), 1).'%</td></tr>';
	$result = '/* '.$file.' */'."\n".$result."\n";
	return $result;
}


echo '<h2>Minify CSS</h2>

<table>';
$txt_css .= compress_file('lib/kickstart/css/kickstart.css', 'css');
$txt_css .= compress_file('lib/kickstart/css/kickstart-buttons.css', 'css');
$txt_css .= compress_file('lib/kickstart/css/kickstart-forms.css', 'css');
$txt_css .= compress_file('lib/kickstart/css/kickstart-menus.css', 'css');
//$txt_css .= compress_file('lib/kickstart/css/kickstart-grid.css', 'css');
$txt_css .= compress_file('lib/kickstart/css/kickstart-icons.css', 'css');
$txt_css .= compress_file('lib/kickstart/css/jquery.fancybox-1.3.4.css', 'css');
$txt_css .= compress_file('lib/kickstart/css/prettify.css', 'css');
$txt_css .= compress_file('lib/kickstart/css/chosen.css', 'css');
$txt_css .= compress_file('lib/kickstart/css/tiptip.css', 'css');
$txt_css .= compress_file('style2.css', 'css');
file_put_contents($root_dir.'img/style_all.css', $txt_css);
echo '</table>';

echo '<h2>Minify JS</h2>

<table>';
$txt_js .= compress_file('lib/kickstart/js/prettify.js', 'js');
$txt_js .= compress_file('lib/kickstart/js/kickstart.js', 'js');
$txt_js .= compress_file('scripts2.js', 'js');
file_put_contents($root_dir.'img/scripts_all.js', $txt_js);
echo '</table>';

?>