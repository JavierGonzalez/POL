<?php
/* The source code packaged with this file is Free Software, Copyright (C) 2008 by
** Javier González González <desarrollo AT virtualpol.com> <gonzomail AT gmail.com>
** It's licensed under the GNU GENERAL PUBLIC LICENSE v3 unless stated otherwise.
** You can get copies of the licenses here: http://www.gnu.org/licenses/gpl.html
** The source: http://www.virtualpol.com/codigo - TOS: http://www.virtualpol.com/TOS
** VirtualPol, The first Democratic Social Network - http://www.virtualpol.com
*/

include('inc-login.php');


if ($_GET['a']) {

	$result = mysql_query("SELECT 
ID, siglas, nombre, descripcion, fecha_creacion, ID_presidente, 
(SELECT nick FROM users WHERE ID = partidos.ID_presidente LIMIT 1) AS nick_presidente
FROM partidos 
WHERE pais = '".PAIS."' AND siglas = '" . trim($_GET['a']) . "'
LIMIT 1", $link);
	while($r = mysql_fetch_array($result)){

		if (($_GET['b'] == 'editar')) { //edit/  AND ($r['ID_presidente'] == $pol['user_ID'])

			$txt_title = 'Editar Partidos';
			$txt_nav = array('/partidos'=>'Partidos', '/partidos/'.$_GET['a']=>$r['siglas'].' ('.$r['nombre'].')', 'Editar');

			//print listas
			$candidatos_num = 0;
			$result2 = mysql_query("SELECT user_ID, orden, 
(SELECT nick FROM users WHERE ID = partidos_listas.user_ID LIMIT 1) AS nick,
(SELECT cargo FROM users WHERE ID = partidos_listas.user_ID LIMIT 1) AS cargo,
(SELECT voto_confianza FROM users WHERE ID = partidos_listas.user_ID LIMIT 1) AS confianza
FROM partidos_listas
WHERE pais = '".PAIS."' AND ID_partido = '" . $r['ID'] . "'
ORDER BY ID ASC", $link);
			while($r2 = mysql_fetch_array($result2)){ 
				if ((!$li_listas) AND (ECONOMIA)) {  $li_presi = ' &larr; Candidato a Presidente'; } else { $li_presi = ''; }
				$li_listas .= '<li><form action="/accion.php?a=partido-lista&b=del&ID=' . $r['ID'] . '" method="post"><input type="hidden" name="user_ID" value="' . $r2['user_ID'] . '"  /><input style="height:26px;" type="submit" value="X" /> <img src="'.IMG.'cargos/'.$r2['cargo'].'.gif" /><b>' . crear_link($r2['nick']) . '</b> ' . $li_presi . '</form></li>' . "\n"; 
				$candidatos_num++;
			}

			$ciudadanos_num = 0;
			$result2 = mysql_query("SELECT ID, nick, fecha_last, voto_confianza,
(SELECT user_ID FROM partidos_listas WHERE pais = '".PAIS."' AND ID_partido = '" . $r['ID'] . "' AND user_ID = users.ID LIMIT 1) AS en_lista, 
(SELECT user_ID FROM cargos_users WHERE cargo_ID = '6' AND user_ID = users.ID AND aprobado = 'ok' LIMIT 1) AS es_diputado
FROM users 
WHERE estado != 'validar' AND estado != 'desarrollador' AND partido_afiliado = '" . $r['ID'] . "' AND pais = '".PAIS."'
ORDER BY nick DESC", $link);
			while($r2 = mysql_fetch_array($result2)){
				if ((!$r2['en_lista']) AND ($r2['es_diputado'])) {
					$ciudadanos .= '<option value="' . $r2['ID'] . '">' . $r2['nick'] . ' (' . confianza($r2['voto_confianza']) . ', ' . duracion(time() - strtotime($r2['fecha_last'])) . ')</option>';
					$ciudadanos_num++;
				}
				$ciudadanos_full .= '<option value="' . $r2['ID'] . '">' . $r2['nick'] . ' (' . confianza($r2['voto_confianza']) . ', ' . duracion(time() - strtotime($r2['fecha_last'])) . ')</option>';
			}

			$text = $r['descripcion'];

			if ($ciudadanos_num == 0) { $disabled = ' disabled="disabled"'; } else { $disabled = ''; }

			include('inc-functions-accion.php');
			$txt .= '<h1><a href="/partidos">Partidos</a>: ' . $r['siglas'] . ' (' . $r['nombre'] . ')</h1>
<ul id="partido">

<li><form action="/accion.php?a=partido-lista&b=add&ID=' . $r['ID'] . '" method="post"><select name="user_ID">' . $ciudadanos . '</select> <input type="submit" value="A&ntilde;adir a la lista"' . $disabled . ' /> (afiliados a tu partido)</form><br /></li>

<li><b>Lista:</b> Candidatos (' . $candidatos_num . ')
<ol>
' . $li_listas . '
</ol><br />
</li>


<li>Introducci&oacute;n, descripci&oacute;n:
<form action="/accion.php?a=partido-lista&b=edit&ID=' . $r['ID'] . '" method="post">
' . editor_enriquecido('text', $text) . '
<input type="submit" value="Guardar" /><br /><br /></form></li>


<li><form action="/accion.php?a=partido-lista&b=del-afiliado&ID=' . $r['ID'] . '" method="post"><select name="user_ID">' . $ciudadanos_full . '</select> <input type="submit" value="Desafiliar" /></form><br /></li>


<li><form action="/accion.php?a=partido-lista&b=ceder-presidencia&ID=' . $r['ID'] . '" method="post"><select name="user_ID">' . $ciudadanos_full . '</select> <input type="submit" value="Ceder Presidencia" onClick="if (!confirm(\'&iquest;Estas convencido de que quieres CEDER tu cargo de Presidente de ' . $r['siglas'] . ' para siempre?\')) { return false; }" /> (Ceder&aacute;s el control total a este ciudadano)</form></li>
</ul>';



		} else {

			//print listas
			$num_listas = 0;
			$result2 = mysql_query("SELECT user_ID,
(SELECT nick FROM users WHERE ID = partidos_listas.user_ID LIMIT 1) AS nick,
(SELECT voto_confianza FROM users WHERE ID = partidos_listas.user_ID LIMIT 1) AS confianza,
(SELECT fecha_last FROM users WHERE ID = partidos_listas.user_ID LIMIT 1) AS fecha_last
FROM partidos_listas
WHERE pais = '".PAIS."' AND ID_partido = '" . $r['ID'] . "'
ORDER BY ID ASC", $link);
			while($r2 = mysql_fetch_array($result2)){ 
				$li_presi = '';
				if ((!ASAMBLEA) AND (!$li_listas)) {  $li_presi = ' &larr; Candidato a Presidente'; }
				if ((!ASAMBLEA) AND ($r['ID_presidente'] == $r2['user_ID'])) {  $li_presi .= ' &larr; Presidente de ' . $r['siglas']; }
				$li_listas .= '<li><b>' . crear_link($r2['nick']) . '</b> ('.(ECONOMIA?confianza($r2['confianza']).', ':'').duracion(time() - strtotime($r2['fecha_last'])) . ')' . $li_presi . '</li>' . "\n";
				$num_listas++;
			}


				$result3 = mysql_query("SELECT nick, estado
FROM users
WHERE partido_afiliado = '" . $r['ID'] . "' AND pais = '".PAIS."' AND estado = 'ciudadano'
ORDER BY fecha_registro ASC", $link);
				while($r3 = mysql_fetch_array($result3)){ 
					$num_afiliados++;
					$afiliados .= ' ' . crear_link($r3['nick'], 'nick', $r3['estado']) . ','; 
				}

			$txt_title = $r['siglas'] . ' - ' . $r['nombre'];
			$txt_nav = array('/partidos'=>'Partidos', '/partidos/'.$_GET['a']=>$r['siglas'].' ('.$r['nombre'].')');

			$txt .= '<h1><a href="/partidos/">Partidos</a>: ' . $r['siglas'] . ' | ' . $r['nombre'] . '</h1>

<p>' . $r['descripcion'] . '</p>

<ul id="partido">
'.(ECONOMIA?'<li>Presidente de ' . $r['siglas'] . ': <b>' . crear_link($r['nick_presidente']) . '</b><br /><br /></li>':'').'
<li>Afiliados: <b>' . $num_afiliados . '</b>' . $afiliados . '<br /><br /></li>
<li>Lista: <b>' . $num_listas . ' (Candidatos)</b>
<ol>
' . $li_listas . '</ol></li>
</ul>';

		}
		$siglas_lower = strtolower($r['siglas']);
		$txt .= '<hr style="width:100%;" />';
		if (($r['ID_presidente'] == $pol['user_ID']) AND (!$_GET['b'])) { //PARA PRESIDENTE
			$txt .= '<span><form><input type="button" value="Editar" onClick="window.location.href=\'/partidos/' . $siglas_lower . '/editar/\';" /> <a href="/partidos/"><b>Ver partidos</b></a></form></span>';
		} elseif ($_GET['b']) { $txt .= '<span style="float:right;"><form><input type="button" value="Eliminar" onClick="if (!confirm(\'&iquest;Estas convencido de que quieres ELIMINAR para siempre?\')) { return false; } else { window.location.href=\'/accion.php?a=eliminar-partido&siglas=' . $r['siglas'] . '\'; }"></form></span><span><a href="/partidos/' . $siglas_lower . '/"><b>Volver</b></a></span>';
		} else { $txt .= '<span>' . boton('Afiliarse', '/form/afiliarse/' . $siglas_lower . '/') . ' <a href="/partidos/"><b>Ver todos</b></a></span>'; }


	} if (!$txt) { /*404*/ }


	$txt_header .= '<style type="text/css">#partido li { margin-top:5px; }</style>';
} else {

	$txt .= '
<table border="0">
<tr>
<th>Siglas</th>
<th>Nombre</th>
<th><acronym title="Afiliados/Candidatos">Afiliados</acronym>&darr;</th>
<th>Presidente</th>
<th><acronym title="Candidato a Presidente de '.PAIS.'">Candidato</acronym></th>
<th>Antig&uuml;edad</th>
<th><acronym title="Participaci&oacute;n en Elecciones">Elec *</acronym></th>
<th>ID</th>
</tr>';



	$result = mysql_query("SELECT ID, siglas, nombre, fecha_creacion, ID_presidente,
(SELECT nick FROM users WHERE ID = partidos.ID_presidente LIMIT 1) AS nick_presidente, 
(SELECT (SELECT nick FROM users WHERE ID = partidos_listas.user_ID LIMIT 1) AS nick FROM partidos_listas WHERE pais = '".PAIS."' AND ID_partido = partidos.ID ORDER BY ID ASC LIMIT 1) AS nick_candidato, 
(SELECT COUNT(ID) FROM users WHERE partido_afiliado = partidos.ID AND pais = '".PAIS."' AND estado = 'ciudadano' LIMIT 1) AS afiliados, 
(SELECT COUNT(ID) FROM partidos_listas WHERE pais = '".PAIS."' AND ID_partido = partidos.ID LIMIT 1) AS num_lista
FROM partidos 
WHERE pais = '".PAIS."' AND estado = 'ok'
ORDER BY num_lista DESC, afiliados DESC, nombre DESC", $link);
	while($r = mysql_fetch_array($result)){

		$num_lista = $r['num_lista'];
		if ($num_lista > 0) {			
			if ($num_lista >= 1) { $num_lista = '<b>' . $num_lista . '</b>'; $elecciones = '<b style="color:blue;">Si</b>'; } else { $elecciones = '<b style="color:red;">No</b>'; }

			if ($r['nick_candidato']) { $nick_candidato = '<b>' . crear_link($r['nick_candidato']) . '</b>'; } else { $nick_candidato = ''; }

			$txt .= '<tr><td align="right" valign="top"><b style="font-size:20px;">' . crear_link($r['siglas'], 'partido') . '</b></td><td>' . $r['nombre'] . '</td><td><b>' . $r['afiliados'] . '</b>/' . $num_lista . '</td><td>' . crear_link($r['nick_presidente']) . '</td><td>' . $nick_candidato . '</td><td align="right">' . duracion(time() - strtotime($r['fecha_creacion'])) . '</td><td>' . $elecciones . '</td><td align="right">'.$r['ID'].'</td></tr>' . "\n";
		} else {
			$txt_otros .= '<span title="'.$r['afiliados'].' afiliados / '.strip_tags($num_lista).' candidatos">'.crear_link($r['siglas'], 'partido').'</span> ';
		}
	}
	$txt .= '</table><p style="width:700px;">Partidos que no participan en elecciones:<br />
<b>'.$txt_otros.'</b></p>';

	$txt .= (ECONOMIA?'<p>* Para poder participar en las Elecciones ha de tener al menos un candidato en su lista. Para poder a&ntilde;adir candidatos en la lista, se ha de ser el Presidente, el candidato ha de estar afiliado y con el examen de Diputado aprobado.</p>':'');
	$txt_title = 'Partidos';
	$txt_nav = array('/partidos'=>'Partidos');
	if (nucleo_acceso($vp['acceso']['crear_partido'])) { $txt_tab = array('/form/crear-partido'=>'Crear partido'); }
}




//THEME
$txt_menu = 'demo';
include('theme.php');
?>