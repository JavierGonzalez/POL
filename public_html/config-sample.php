<?php
date_default_timezone_set('Europe/Madrid');

// INICIALIZACION
define('DOMAIN', 'virtualpol.com');
define('RAIZ', dirname(__FILE__).'/');
$host = explode('.', $_SERVER['HTTP_HOST']); // obtiene $host[0] que es el subdominio
$host[0] = str_replace('-dev', '', $host[0]); // convierte subdominios "pais-dev" en "pais" para que funcione la version dev

// Passwords y claves
include(RAIZ.'config-pwd.php');

// PLATAFORMAS ACTIVAS (TAMBIEN LLAMADOS: PAISES)
$vp['paises'] = array('15M', 'Hispania', 'RSSV');

// CONFIG PLATAFORMAS
$vp['paises_congelados'] = array('POL', 'VULCAN', 'Atlantis', 'VP'); // INACTIVOS
$vp['paises_economia'] = array('Hispania'); // Plataformas con economia
$vp['paises_chat'] = array(''=>4, 'VP'=>4, '15M'=>5, 'Hispania'=>6, 'RSSV'=>7);
$vp['bg'] = array('POL'=>'#E1EDFF', 'Hispania'=>'#FFFF4F', 'RSSV'=>'#FFD7D7', 'Atlantis'=>'#B9B9B9', 'VP'=>'#CAF0FF', '15M' => '#FFFFB0', 'www'=>'#eeeeee');
$vp['bg2'] = array('POL'=>'#BFD9FF', 'Hispania'=>'#D9D900', 'RSSV'=>'#999999', 'Atlantis'=>'#999999', 'VP'=>'#71D8FF', '15M' => '#FFFF64', 'www'=>'grey');


// CONFIGURACION ESPECIFICA DE PLATAFORMAS
switch ($host[0]) { 
	case '15m':
		define('PAIS', '15M'); 
		define('ASAMBLEA', true); 
		define('ECONOMIA', false);
		define('NOM_PARTIDOS','Grupos');
		$vp['acceso'] = array(
'votacion_borrador'=>	array('ciudadanos', ''),
'sondeo'=>				array('cargo', '6'),
'referendum'=>			array('cargo', '6'),
'parlamento'=>			array('cargo', '6'),
'cargo'=>				array('cargo', '6'),
'kick'=>				array('cargo', '13'),
'kick_quitar'=>			array('cargo', '6 13'),
'foro_borrar'=>			array('cargo', '6 13'),
'control_gobierno'=>	array('cargo', '6'),
'control_sancion'=>		array('cargo', ''),
'control_grupos'=>		array('cargo', '6'),
'control_cargos'=>		array('cargo', '6'),
'examenes_decano'=>		array('cargo', '6'),
'examenes_profesor'=>	array('privado', ''),
'crear_partido'=>		array('cargo', '6'),
);
		break;

	case 'hispania': 
		define('PAIS', 'Hispania'); 
		define('ASAMBLEA', false);
		define('ECONOMIA', true); 
		define('NOM_PARTIDOS','Partidos');
		$columnas = 14; $filas = 14;
		$vp['acceso'] = array(
'votacion_borrador'=>	array('ciudadanos_global', ''),
'sondeo'=>				array('cargo', '41 6 16 22 19 7'),
'referendum'=>			array('nivel', '95'),
'parlamento'=>			array('cargo', '6 22'),
'cargo'=>				array('nivel', '85'),
'kick'=>				array('cargo', '12 13 22 9'),
'kick_quitar'=>			array('cargo', '13 9 8'),
'foro_borrar'=>			array('cargo', '12 13'),
'control_gobierno'=>	array('cargo', '7 19'),
'control_sancion'=>		array('cargo', '9'),
'control_grupos'=>		array('cargo', '7 19'),
'control_cargos'=>		array('cargo', '7 19'),
'examenes_decano'=>		array('cargo', '35 60'),
'examenes_profesor'=>	array('cargo', '34'),
'crear_partido'=>		array('antiguedad','0'),
);
		break;

	case 'rssv': 
		define('PAIS', 'RSSV'); 
		define('ASAMBLEA', true);
		define('ECONOMIA', false); 
		define('NOM_PARTIDOS','Grupos');
		$vp['acceso'] = array(
'votacion_borrador'=>	array('ciudadanos', ''),
'sondeo'=>				array('cargo', '6'),
'referendum'=>			array('cargo', '6'),
'parlamento'=>			array('cargo', '6'),
'cargo'=>				array('cargo', '6'),
'kick'=>				array('cargo', '13'),
'kick_quitar'=>			array('cargo', '6 13'),
'foro_borrar'=>			array('cargo', '6 13'),
'control_gobierno'=>	array('cargo', '6'),
'control_sancion'=>		array('cargo', ''),
'control_grupos'=>		array('cargo', '6'),
'control_cargos'=>		array('cargo', '6'),
'examenes_decano'=>		array('cargo', '6'),
'examenes_profesor'=>	array('privado', ''),
'crear_partido'=>		array('cargo', '6'),
);
		break;


	// PLATAFORMAS INACTIVAS
	case 'pol': define('PAIS', 'POL'); break;
	case 'vulcan': define('PAIS', 'Vulcan'); break;
	case 'atlantis': define('PAIS', 'Atlantis'); break;
	case 'vp': define('PAIS', 'VP'); break;
	default: define('PAIS', 'Hispania'); break;
}


// URLS (SSL, IMG, REGISTRAR)
define('SSL_URL', 'https://'.DOMAIN.'/'); // SSL_URL | http://www.virtualpol.com/ = https://virtualpol.com/
if ($_SERVER['HTTPS']) {
	define('IMG', 'https://'.DOMAIN.'/img/');
} else {
	define('IMG', 'http://www.'.DOMAIN.'/img/');;
}
define('REGISTRAR', 'https://'.DOMAIN.'/registrar/');


// CONFIG SISTEMA
define('CONTACTO_EMAIL', 'desarrollo@virtualpol.com');
define('USERCOOKIE', '.'.DOMAIN);

define('COLOR_BG', $vp['bg'][PAIS]);
define('COLOR_BG2', $vp['bg2'][PAIS]);

define('HOST', $_SERVER['HTTP_HOST']);
define('VERSION', '1.0 Beta'); // ELIMINAR?
define('MONEDA', '<img src="'.IMG.'varios/m.gif" border="0" />');
define('MONEDA_NOMBRE', 'monedas');
define('VOTO_CONFIANZA_MAX', 50); // numero maximo de votos de confianza emitibles
$datos_perfil = array('Blog', 'Twitter', 'Facebook', 'Google+', '', 'Menéame');

// CONFIG SQL
define('SQL', strtolower(PAIS).'_');
define('SQL_USERS', 'users'); // ELIMINAR?
define('SQL_REFERENCIAS', 'referencias');
define('SQL_MENSAJES', 'mensajes');
define('SQL_VOTOS', 'votos'); // ELIMINAR?
define('SQL_EXPULSIONES', 'expulsiones');

?>