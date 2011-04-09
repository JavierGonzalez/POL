<?php 
include('inc-login.php');

/* Modulo del Ejercito.

Este modulo tiene la intención de ayudar al ejercito en sus gestiones. Normalmente será privado para los cargos del Ejercito.
*/

// load user cargos
$pol['cargos'] = cargos();



switch ($_GET['a']) {


case 'contingente':
	// Este codigo sirve para mostrar el listado de soldados y capitanes junto con su email, con el objetivo de facilitar el traspaso del acceso en Google Docs.
	$txt_title = 'Contingente';
	if (($pol['cargos'][57]) OR ($pol['cargos'][7])) {
		$txt .= '<h1>Ejercito: Contingente (<a href="/cargos/55/">Editar Cargo</a> | <a href="/foro/ejercito-de-vp/aspirantes-a-soldado-de-vp/">Aspirantes</a>)</h1><br />';


		$result = mysql_query("SELECT user_ID, ID_estudio, 
(SELECT email FROM users WHERE ID = ".SQL."estudios_users.user_ID LIMIT 1) AS email, 
(SELECT nick FROM users WHERE ID = ".SQL."estudios_users.user_ID LIMIT 1) AS nick 
FROM ".SQL."estudios_users 
WHERE ID_estudio IN (55, 57) AND estado = 'ok' AND cargo = '1'
ORDER BY ID_estudio DESC, time ASC", $link);
		while($r = mysql_fetch_array($result)) { 
			if (($cargo_last != $r['ID_estudio']) AND ($cargo_last)) { $txt .= '<br />'; }
			$txt .= '"'.$r['nick'].'" &lt;'.$r['email'].'&gt;,<br />';
			$cargo_last = $r['ID_estudio'];
		}

		$txt .= '<br /><br /><br /><br />';
		
	}

	break;

}



//THEME
include('theme.php');
?>
