<?php

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
	'en_US'=>'English (Experimental)',
);

// CONFIG PLATAFORMAS (pendiente de desmantelar)
$vp['paises'] = array('15M', 'Hispania', 'RSSV'); // PLATAFORMAS ACTIVAS (TAMBIEN LLAMADOS: PAISES)
$vp['paises_chat'] = array(''=>4, 'VP'=>4, '15M'=>5, 'Hispania'=>6, 'RSSV'=>7);
$vp['bg'] = array('POL'=>'#E1EDFF', 'Hispania'=>'#FFFF4F', 'RSSV'=>'#FFD7D7', 'Atlantis'=>'#B9B9B9', 'VP'=>'#CAF0FF', '15M' => '#FFFFB0', 'www'=>'#eeeeee');

switch ($pais) { 
	case '15m': break;
	case 'hispania': break;
	case 'rssv': break;

	// PLATAFORMAS INACTIVAS
	case 'pol':			define('PAIS', 'POL'); break;
	case 'vulcan':		define('PAIS', 'Vulcan'); break;
	case 'atlantis':	define('PAIS', 'Atlantis'); break;
	case 'vp':			define('PAIS', 'VP'); break;
	
	case 'www': case '': case 'virtualpol': define('PAIS', 'Ninguno'); break; 
	default: header('HTTP/1.1 301 Moved Permanently'); header('Location: http://www.virtualpol.com'); exit;
}


// CONFIG
define('DOMAIN', 'virtualpol.com');
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