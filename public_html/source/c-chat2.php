<?php 

/* ### Proyecto CHAT 2 ###

2010-04-16 <GONZO> La idea consiste en desarrollar un nuevo chat que cuando esté listo suplantará al antiguo sistema, con el mismo nucleo pero con una evolución sustancial.


MEJORAS:
1. Crear una tabla en BD de chats activos. Esto nos permitirá: crear y eliminar chats flexiblemente, configurar parámetros por cada chat (por ejemplo habilitar/deshabilitar extranjeros o anonimos), acceso privado.
3. Centralizar las tablas de chat en una unica a ser posible.
2. Crear una versión html del chat puro para que sirva como recurso a webs externas (a modo de widget, con una linea de js). Esto expandirá la comunidad y aportará un sentido util y práctico de existir (moderar, vigilar y controlar los chats basandonos en nuestro sistema democrático).
3. Permitir acceso opcional a anonimos sin registrar y sus correspondientes medidas de control y mitigación (por ejemplo un comando para avisar a un policia).
4. ¿Ideas?


TAREAS:		(- por hacer, x hecho)
- Crear nueva tabla "chats".
- Crear nueva tabla "chats_msg".
- Copiar el nucleo del chat que servirá como comienzo.
- Aglutinar el HTML, CSS y JS del chat, aislandolo del resto del codigo.
- Sortear los conflictos derivados de que hay multiples paises.
- Panel de creación de nuevos chats. Opciones:
	- Tiempo de expiración: tras N dias de inactividad, perpetuo. 
	- Qué pais lo Gobierna en ultima instancia (con sus policias o incluso pudiendolo clausurar).
- Panel de configuracion de un chat existente. Opciones:
	- Acceso minimo para lectura: N nivel, N antiguedad, ciudadanos de X pais, cualquier ciudadano, abierto.
	- Acceso minimo para escritura: N nivel, N antiguedad, ciudadanos de X pais, cualquier ciudadano, anonimos.
	- ...
- Añadir en Despacho Oval control para quien puede crear un chat,
- Página mostrando los chats activos, su URL externa donde se visualiza (si la hay) y estadisticas de visitas de los chats.
- ...



*/


include('inc-login.php');
$adsense_exclude = true;
$pol['chat_accesos'] = false;

switch ($_GET['a']) {

case 'plaza':
	$pol['chat_id'] = 0;
	$pol['chat_nombre'] = 'Plaza de '.PAIS;


	//redireccion
	if ($_GET['b'] != 'm') { header('Location: http://'.strtolower(PAIS).'.virtualpol.com/'); exit; }

	break;

case 'parlamento':
	$pol['chat_id'] = 1;
	$pol['chat_nombre'] = 'Parlamento de '.PAIS;
	break;

case 'comisaria':
	$pol['chat_id'] = 2;
	$pol['chat_nombre'] = 'Comisaria de '.PAIS;
	break;

case 'tribunales':
	$pol['chat_id'] = 3;
	$pol['chat_nombre'] = 'Tribunales de '.PAIS;
	break;

case 'gobierno':
	$pol['chat_id'] = 4;
	$pol['chat_nombre'] = 'Gobierno de '.PAIS;
	break;

case 'hotel-arts':
	$pol['chat_id'] = 5;
	$pol['chat_nombre'] = 'Hotel Arts de '.PAIS;
	break;

case 'universidad':
	$pol['chat_id'] = 6;
	$pol['chat_nombre'] = 'Universidad de '.PAIS;
	break;

case 'antiguedad':
	if (strtotime($pol['fecha_registro']) < (time() - 2592000)) {
		$pol['chat_id'] = 7;
		$pol['chat_nombre'] = 'Antiguedad de '.PAIS;
	}
	break;

case 'anfiteatro':
	if (PAIS == 'POL') {
		$pol['chat_id'] = 8;
		$pol['chat_nombre'] = 'Anfiteatro (Club <a href="http://pol.virtualpol.com/foro/general/nacimiento-del-club-privado-de-debate-mmmmm-y-de-la-ongd-baobab/">mmmmm</a>)';
		$pol['chat_accesos'] = true;
		$pol['chat_accesos_list'] = array('Jazunzu', 'born', 'Sanchez', 'GONZO', 'dannnyql', 'selvatgi', 'fran');
	}
	break;
	
	
	default: header('Location: http://'.HOST.'/'); break;
}


if (($_GET['a']) AND (isset($pol['chat_id']))) {

	include('inc-chat.php');
	
	$txt_title = strip_tags('CHAT: ' . $pol['chat_nombre']);
}

if ($_GET['b'] == 'm') { include('theme-m.php'); } else { include('theme.php'); }

?>
