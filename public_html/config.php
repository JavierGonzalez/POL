<?php
// Dominio
define('URL', 'virtualpol.com');

// INICIALIZACION
$host = explode('.', $_SERVER['HTTP_HOST']); // obtiene $host[0] que es el subdominio
$host[0] = str_replace('-dev', '', $host[0], $dev); // convierte subdominios "pais-dev" en "pais" para que funcione la version dev
if ($host[1] != 'virtualpol') { header('HTTP/1.1 301 Moved Permanently'); header('Location: http://www.virtualpol.com/'); exit; }
if ($dev) { define('DEV', '-dev'); } else { define('DEV', ''); }

// Configuracion Paises y colores
$vp['paises_congelados'] = array('POL', 'VULCAN', 'Hispania', 'Atlantis'); // aqui los paises que pasaron a ser historia (visibles pero inactivos)
$vp['paises'] = array('VP', 'POL', 'VULCAN', 'Hispania', 'Atlantis'); // Quitar paises congelados cuando esté listo el estado "congelado".
$vp['bg'] = array('POL'=>'#E1EDFF', 'Hispania'=>'#FFFF4F', 'Atlantis'=>'#B9B9B9', 'vp'=>'#ACFA58', 'www'=>'#eeeeee');
$vp['bg2'] = array('POL'=>'#BFD9FF', 'Hispania'=>'#D9D900', 'Atlantis'=>'#999999', 'vp'=>'#9AFE2E', 'www'=>'grey');


// Configuracion por pais
switch ($host[0]) { 

case 'vp':
	define('PAIS', 'VP');
	define('SQL', 'vp_');
	break;

case 'pol':
	define('PAIS', 'POL');
	define('SQL', 'pol_');
	break;

case 'vulcan':
	define('PAIS', 'Vulcan');
	define('SQL', 'vulcan_');
	break;

case 'hispania':
	define('PAIS', 'Hispania');
	define('SQL', 'hispania_');
	break;

case 'atlantis':
	define('PAIS', 'Atlantis');
	define('SQL', 'atlantis_');
	break;

default:
	define('PAIS', 'POL');
	define('SQL', 'pol_');
	break;
}

define('COLOR_BG', $vp['bg'][PAIS]);
define('COLOR_BG2', $vp['bg2'][PAIS]);

// variables del sistema
if (DEV == '-dev') {
	// Version DEV
	define('RAIZ', '/var/www/vhosts/virtualpol.com/httpdocs/devel/');
} else {
	// version REAL (www.virtualpol.com)
	define('RAIZ', '/var/www/vhosts/virtualpol.com/httpdocs/real/');
}

define('HOST', $_SERVER['HTTP_HOST']);
define('VERSION', '1.0 Beta');
define('IMG', 'http://www'.DEV.'.virtualpol.com/img/'); // Directorio en el que deben ir todos los elementos estáticos (gif, jpg, css, js)

define('MONEDA', '<img src="'.IMG.'m.gif" border="0" />');
define('MONEDA_NOMBRE', 'monedas');
// variables de tablas SQL
define('SQL_USERS', 'users');
define('SQL_REFERENCIAS', 'referencias');
define('SQL_MENSAJES', 'mensajes');
define('SQL_VOTOS', 'votos');
define('SQL_EXPULSIONES', 'expulsiones');

// variables del sistema de usuarios
define('USERCOOKIE', '.virtualpol.com');
define('REGISTRAR', 'http://www'.DEV.'.virtualpol.com/registrar/');

// mapa (provisional)
$columnas = 14;
$filas = 18;

// funciones con passwords importantes
include(RAIZ.'config-pwd.php');
?>
