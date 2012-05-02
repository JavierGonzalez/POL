<?php
/* The source code packaged with this file is Free Software, Copyright (C) 2008 by
** Javier González González <desarrollo AT virtualpol.com> <gonzomail AT gmail.com>
** It's licensed under the GNU GENERAL PUBLIC LICENSE v3 unless stated otherwise.
** You can get copies of the licenses here: http://www.gnu.org/licenses/gpl.html
** The source: http://www.virtualpol.com/codigo - TOS: http://www.virtualpol.com/TOS
** VirtualPol, The first Democratic Social Network - http://www.virtualpol.com
*/


// INICIALIZACION
date_default_timezone_set('Europe/Madrid');
define('RAIZ', dirname(__FILE__).'/');
include(RAIZ.'config-pwd.php');						// Passwords y claves
include(RAIZ.'source/inc-functions.php');			// Funciones basicas
$link = conectar();									// Conecta MySQL
$pais = explodear('.', $_SERVER['HTTP_HOST'], 0);


// LOAD CONFIG
$result = mysql_unbuffered_query("SELECT dato, valor FROM config WHERE pais = '".escape($pais)."' AND autoload = 'si'");
while ($r = r($result)) { 
	switch ($r['dato']) {
		case 'PAIS': define('PAIS', $r['valor']); break;
		case 'ASAMBLEA': case 'ECONOMIA':  define($r['dato'], ($r['valor']=='true'?true:false)); break;

		case 'acceso': 
			foreach(explode('|', $r['valor']) AS $item) {
				$elem = explode(';', $item);
				$vp['acceso'][$elem[0]] = array(explodear(':', $elem[1], 0), explodear(':', $elem[1], 1));
			}
			break;

		default: $pol['config'][$r['dato']] = $r['valor']; 
	} 
}

// LENGUAJES ACTIVADOS
$vp['langs'] = array(
'es_ES'=>'Español',
'en_US'=>'English (30%)',
'ca_ES'=>'Català (30%)',
'eu'=>'Euskera (30%)',
'gl_ES'=>'Galego (30%)',
'de_DE'=>'Deutsch (30%)',
);

// CONFIG PLATAFORMAS (pendiente de desmantelar)
$vp['paises'] = array('15M', 'Hispania', 'DRY', 'MIC'); // PLATAFORMAS ACTIVAS (TAMBIEN LLAMADOS: PAISES)
$vp['paises_chat'] = array(''=>4, 'VP'=>4, '15M'=>5, 'Hispania'=>6, 'MIC'=>7, 'DRY'=>1);
$vp['bg'] = array('POL'=>'#E1EDFF', 'Hispania'=>'#FFFF4F', 'MIC'=>'#FFD7D7', 'Atlantis'=>'#B9B9B9', 'VP'=>'#CAF0FF', '15M' => '#FFFFB0', 'DRY' => '#FBDB03', 'www'=>'#eeeeee');

//CONFIG DOMAIN, WITHOUT WWW
define('DOMAIN', 'virtualpol.com');

switch ($pais) { 
	case '15m': break;
	case 'hispania': break;
	case 'mic': break;
	case 'dry': break;

	// PLATAFORMAS INACTIVAS
	case 'pol':			define('PAIS', 'POL'); break;
	case 'vulcan':		define('PAIS', 'Vulcan'); break;
	case 'atlantis':	define('PAIS', 'Atlantis'); break;
	case 'vp':			define('PAIS', 'VP'); break;
	
	case 'www': case '': case 'virtualpol': define('PAIS', 'Ninguno'); break; 
	default: header('HTTP/1.1 301 Moved Permanently'); header('Location: http://www.'.DOMAIN); exit;
}


// CONFIG
//define('DOMAIN', 'virtualpol.com');
define('SQL', strtolower(PAIS).'_');
define('CONTACTO_EMAIL', 'desarrollo@virtualpol.com');
define('USERCOOKIE', '.'.DOMAIN);
define('HOST', $_SERVER['HTTP_HOST']);
define('VOTO_CONFIANZA_MAX', 50); // numero maximo de votos de confianza emitibles
$datos_perfil = array('Blog', 'Twitter', 'Facebook', 'Google+', '', 'Menéame');
$columnas = 14; $filas = 14; // Dimensiones mapa

// URLS (SSL, IMG, REGISTRAR)
define('REGISTRAR', 'https://'.DOMAIN.'/registrar/');
define('SSL_URL', 'https://'.DOMAIN.'/'); // SSL_URL | http://www.virtualpol.com/ = https://virtualpol.com/
if ($_SERVER['HTTPS']) {
	define('IMG', 'https://'.DOMAIN.'/img/');
} else {
	define('IMG', 'http://www.'.DOMAIN.'/img/');;
}

define('MONEDA', '<img src="'.IMG.'varios/m.gif" border="0" />');
?>
