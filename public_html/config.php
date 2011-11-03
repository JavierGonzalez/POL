<?php
define('URL', 'virtualpol.com');
define('RAIZ', '/var/www/vhosts/virtualpol.com/httpdocs/real/');

// INICIALIZACION
$host = explode('.', $_SERVER['HTTP_HOST']); // obtiene $host[0] que es el subdominio
$host[0] = str_replace('-dev', '', $host[0]); // convierte subdominios "pais-dev" en "pais" para que funcione la version dev

// Passwords y claves
include(RAIZ.'config-pwd.php');

// PAISES
$vp['paises'] = array('15M', 'VP'); // ACTIVOS
$vp['paises_congelados'] = array('POL', 'VULCAN', 'Hispania', 'Atlantis'); // INACTIVOS
$vp['paises_economia'] = array('VP'); // Plataformas con economia

// COLORES
$vp['bg'] = array('POL'=>'#E1EDFF', 'Hispania'=>'#FFFF4F', 'Atlantis'=>'#B9B9B9', 'VP'=>'#CAF0FF', '15M' => '#FFFFB0', 'www'=>'#eeeeee');
$vp['bg2'] = array('POL'=>'#BFD9FF', 'Hispania'=>'#D9D900', 'Atlantis'=>'#999999', 'VP'=>'#71D8FF', '15M' => '#FFFF64', 'www'=>'grey');

$vp['paises_chat'] = array('VP'=>4, '15M'=>5, ''=>4);

switch ($host[0]) { 
	// PLATAFORMAS ACTIVAS
	case '15m': define('PAIS', '15M'); define('ASAMBLEA', true); break;
	case 'vp': define('PAIS', 'VP'); define('ASAMBLEA', false); break;

	// PLATAFORMAS INACTIVAS
	case 'pol': define('PAIS', 'POL'); break;
	case 'vulcan': define('PAIS', 'Vulcan'); break;
	case 'hispania': define('PAIS', 'Hispania'); break;
	case 'atlantis': define('PAIS', 'Atlantis'); break;
	default: define('PAIS', 'VP'); break;
}


// CONFIGURACION ESPECIFICA DE PLATAFORMAS
if (ASAMBLEA) {
	define('ECONOMIA', false);
	define('NOM_PARTIDOS','Comisiones');
	
	$vp['acceso'] = array(
'sondeo'=>array('cargo', '6'),
'referendum'=>array('cargo', '6'),
'parlamento'=>array('cargo', '6'),
'cargo'=>array('cargo', '6'),
'kick'=>array('cargo', '6 13'),
'kick_quitar'=>array('cargo', '6 13'),
'foro_borrar'=>array('cargo', '6 13'),
'control_gobierno'=>array('cargo', '6'),
'control_sancion'=>array('cargo', ''),
'examenes_decano'=>array('cargo', '6'),
'examenes_profesor'=>array('cargo', ''),
'crear_partido'=>array('cargo','6'),
);

} else { // Plataformas NO asamblearias
	define('ECONOMIA', true); 
	define('NOM_PARTIDOS','Partidos'); 
	
	$vp['acceso'] = array(
'sondeo'=>array('cargo', '41 6 16 22 19 7'),
'referendum'=>array('nivel', '95'),
'parlamento'=>array('cargo', '6 22'),
'cargo'=>array('nivel', '85'),
'kick'=>array('cargo', '12 13 22 9'),
'kick_quitar'=>array('cargo', '13 9 8'),
'foro_borrar'=>array('cargo', '12 13'),
'control_gobierno'=>array('cargo', '7 19'),
'control_sancion'=>array('cargo', '9'),
'examenes_decano'=>array('cargo', '35'),
'examenes_profesor'=>array('cargo', '34'),
'crear_partido'=>array('antiguedad','0'),
);
	$columnas = 14; $filas = 18; // Tamaño del mapa
}


define('SSL_URL', 'https://virtualpol.com/'); // SSL_URL = http://www.virtualpol.com/ | https://virtualpol.com/
if ($_SERVER['HTTPS']) {
	define('IMG', 'https://virtualpol.com/img/');
} else {
	define('IMG', 'http://www.virtualpol.com/img/');
	//define('IMG', 'http://vp.cdn.teoriza.com/');
}
define('REGISTRAR', 'https://virtualpol.com/registrar/');


define('SQL', strtolower(PAIS).'_');

define('COLOR_BG', $vp['bg'][PAIS]);
define('COLOR_BG2', $vp['bg2'][PAIS]);

define('HOST', $_SERVER['HTTP_HOST']);
define('VERSION', '1.0 Beta');
define('MONEDA', '<img src="'.IMG.'varios/m.gif" border="0" />');
define('MONEDA_NOMBRE', 'monedas');
// variables de tablas SQL
define('SQL_USERS', 'users');
define('SQL_REFERENCIAS', 'referencias');
define('SQL_MENSAJES', 'mensajes');
define('SQL_VOTOS', 'votos');
define('SQL_EXPULSIONES', 'expulsiones');
define('VOTO_CONFIANZA_MAX', 40); // numero maximo de votos de confianza emitidos

// Variables del sistema de usuarios
define('USERCOOKIE', '.virtualpol.com');
?>