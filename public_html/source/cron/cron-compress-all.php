<?php 
/* Este script unifica y comprime (minified) archivos js y css por optimización. */

$root_dir = str_replace('source/cron', '', dirname(__FILE__));


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
	echo '<b>'.$tipo.'</b> '.$len_antes.'bytes '.$file;
	switch ($tipo) {
		case 'js': $result = minify_js($result);  break;
		case 'css': 
			$result = minify_css($result); 
			$result = str_replace('url(img/', 'url(lib/kickstart/css/img/', $result); /* corrige URLs de kickstart (codigo malo) */
			$result = str_replace('url(\'img/', 'url(\'lib/kickstart/css/img/', $result); /* corrige URLs de kickstart (codigo malo) */
			break;
	}
	$len_ahora = strlen($result);
	echo ' '.$len_ahora.'bytes (<b>-'.round($len_antes-$len_ahora).'bytes</b>)<br />';
	$result = '/* '.$file.' */'."\n".$result."\n";
	return $result;
}

echo '<h2>Minify CSS</h2>';
$txt_css .= compress_file('style2.css', 'css');
$txt_css .= compress_file('lib/kickstart/css/kickstart.css', 'css');
$txt_css .= compress_file('lib/kickstart/css/kickstart-buttons.css', 'css');
$txt_css .= compress_file('lib/kickstart/css/kickstart-forms.css', 'css');
$txt_css .= compress_file('lib/kickstart/css/kickstart-menus.css', 'css');
//$txt_css .= compress_file('lib/kickstart/css/kickstart-grid.css', 'css');
//$txt_css .= compress_file('lib/kickstart/css/kickstart-icons.css', 'css');
//$txt_css .= compress_file('lib/kickstart/css/jquery.fancybox-1.3.4.css', 'css');
//$txt_css .= compress_file('lib/kickstart/css/prettify.css', 'css');
//$txt_css .= compress_file('lib/kickstart/css/chosen.css', 'css');
//$txt_css .= compress_file('lib/kickstart/css/tiptip.css', 'css');
file_put_contents($root_dir.'img/style_all.css', $txt_css);


echo '<h2>Minify JS</h2>';
$txt_js .= compress_file('lib/kickstart/js/prettify.js', 'js');
$txt_js .= compress_file('lib/kickstart/js/kickstart.js', 'js');
$txt_js .= compress_file('scripts2.js', 'js');
file_put_contents($root_dir.'img/scripts_all.js', $txt_js);

?>