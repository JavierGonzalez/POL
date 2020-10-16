<?php # POL.VirtualPol.com — Copyright (c) 2008 Javier González González <gonzo@virtualpol.com> — MIT License 


ini_set('display_errors', 1);
error_reporting(E_ALL ^ E_NOTICE);


$maxsim['output'] = 'template';


// INICIALIZACION
define('RAIZ', dirname(__FILE__).'/..');

$link = sql_link();

//$pais = explodear('.', $_SERVER['HTTP_HOST'], 0);	// Obtiene "PAIS" de "PAIS.dominio.com"
$pais = 'pol';


// LOAD CONFIG
$result = mysql_query_old("SELECT dato, valor FROM config WHERE pais = '".escape($pais)."' AND autoload = 'si'");
while ($r = r($result)) { 
	switch ($r['dato']) {
		case 'PAIS': define('PAIS', $r['valor']); break;
		
		case 'ASAMBLEA': 
		case 'ECONOMIA':  
			define($r['dato'], ($r['valor']=='true'?true:false)); 
			break;

		case 'acceso': 
			foreach(explode('|', $r['valor']) AS $item) {
				$elem = explode(';', $item);
				$elem1 = explode(':', $elem[1]);
				$vp['acceso'][$elem[0]] = array($elem1[0], $elem1[1]);
			}
			break;

		default: $pol['config'][$r['dato']] = $r['valor'];
	} 
}

// TIMEZONE
date_default_timezone_set((isset($pol['config']['timezone'])?$pol['config']['timezone']:'Europe/Madrid'));

// LENGUAJES ACTIVADOS
$vp['langs'] = array(
'es_ES'=>'Español (100%)',
'en_US'=>'English (95%)',
'fr'=>'Français (70%)',
'ca_ES'=>'Català (70%)',
'gl_ES'=>'Galego (70%)',
'eo'=>'Esperanto (40%)',
'eu'=>'Euskera (20%)',
'de_DE'=>'Deutsch (30%)',
'ja'=>'Japanese (10%)',
'pt'=>'Português (10%)',
);

// CONFIG PLATAFORMAS (pendiente de desmantelar)
$vp['paises'] = array('POL');
// borrados: 'ETSIIT', 'PCP', 'Occupy', 'Plebiscito', 'PDI'
// borrados: '15M', 'Hispania', 'JRO', 'FCSM', '25S', 'POL', 'MIC'
// 2019-08-31 borrados: 'Simulador', 'Asamblea'


if (!defined('PAIS'))
	define('PAIS', 'Ninguno');


// CONFIG
define('DOMAIN', '');
define('CONTACTO_EMAIL', 'desarollo@virtualpol.com');

define('SQL', strtolower(PAIS).'_');
define('USERCOOKIE', '');
define('HOST', $_SERVER['HTTP_HOST']);
define('VOTO_CONFIANZA_MAX', 50); // Máximo de votos de confianza emitibles
define('SC_NUM', 3); // Numero de SC electos.
define('MP_MAX', 25); // Máximo de MP (mensajes privados) que puede enviar un ciudadano
define('VER', 13); // Version de archivos estaticos. Util para forzar el refresco de js y css. Incrementar.
$datos_perfil = array('Blog', 'Twitter', 'Facebook', '', 'Menéame');

$columnas = 11; $filas = 11; // Dimensiones mapa


define('PRE', (substr($_SERVER['HTTP_HOST'],0,4)=='pre-'?true:false));


// URLS

define('SSL_URL', '/');
define('IMG', '/img/'); // Archivos estaticos


define('MONEDA', '<img src="/img/varios/m.gif" />'); 



//INIT
$date = date('Y-m-d H:i:s');
$IP = direccion_IP('longip');

