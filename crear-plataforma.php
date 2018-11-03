<?php
/* The source code packaged with this file is Free Software, Copyright (C) 2008 by
** Javier González González <desarrollo AT virtualpol.com> <gonzomail AT gmail.com>
** It's licensed under the GNU GENERAL PUBLIC LICENSE v3 unless stated otherwise.
** You can get copies of the licenses here: http://www.gnu.org/licenses/gpl.html
** The source: http://www.virtualpol.com/codigo - TOS: http://www.virtualpol.com/TOS
** VirtualPol, The first Democratic Social Network - http://www.virtualpol.com
*/

include('inc-login.php');



if ($_GET['a'] == 'accion') {
	// ACCIONES

	if (($_GET['b'] == 'add') AND (entre(strlen($_POST['pais']), 2, 10)) AND (is_numeric($_POST['participacion'])) AND ($_POST['condiciones_extra']== 'true')) {
		mysql_query("INSERT INTO plataformas (estado, pais, asamblea, economia, user_ID, time, descripcion, participacion) 
VALUES ('pendiente', '".str_replace(' ', '', strip_tags($_POST['pais']))."', '".$_POST['asamblea']."', '".$_POST['economia']."', '".$pol['user_ID']."', '".$date."', '".strip_tags($_POST['descripcion'])."', '".$_POST['participacion']."')", $link);
		$txt .= '<p>Solicitud enviada correctamente.</p>';

	} else { redirect('http://'.HOST.'/?error='.base64_encode('Solicitud erronea.')); }


} elseif (($_GET['a'] == 'admin') AND ($pol['user_ID'] == 1)) {


	if (($_GET['b'] == 'aprobar') AND (is_numeric($_GET['ID']))) {			
		// *** CREAR NUEVA PLATAFORMA ***
		
		$result = sql("SELECT * FROM plataformas WHERE ID = '".$_GET['ID']."' LIMIT 1");
		while($r = r($result)) { 
			
			sql("UPDATE plataformas SET estado = 'creada' WHERE ID = '".$r['ID']."' LIMIT 1");

			$param = array(
'PAIS'=>array('valor'=>$r['pais'], 'autoload'=>'si'),
'pais_des'=>array('valor'=>'Descripci&oacute;n provisional', 'autoload'=>'si'),
'tipo'=>array('valor'=>'plataforma', 'autoload'=>'no'),
'timezone'=>array('valor'=>'Europe/Madrid', 'autoload'=>'si'),
'bg_color'=>array('valor'=>'#eeeeee', 'autoload'=>'si'),

'ECONOMIA'=>array('valor'=>$r['economia'], 'autoload'=>'si'),
'ASAMBLEA'=>array('valor'=>$r['asamblea'], 'autoload'=>'si'),

'lang'=>array('valor'=>'es_ES', 'autoload'=>'si'),
'bg'=>array('valor'=>'tapiz-lineas-verdes.jpg', 'autoload'=>'si'),
'defcon'=>array('valor'=>'5', 'autoload'=>'si'),

'info_documentos'=>array('valor'=>'0', 'autoload'=>'si'),
'info_censo'=>array('valor'=>'0', 'autoload'=>'si'),
'info_partidos'=>array('valor'=>'0', 'autoload'=>'si'),
'info_consultas'=>array('valor'=>'0', 'autoload'=>'si'),

'palabras'=>array('valor'=>'-1::;-1::;-1::;-1::;-1::;-1::;-1::;-1::;-1::;', 'autoload'=>'si'),
'palabras_num'=>array('valor'=>'8', 'autoload'=>'no'),
'palabra_gob'=>array('valor'=>'', 'autoload'=>'si'),

// EXPIRACIONES
'examen_repe'=>array('valor'=>'86400', 'autoload'=>'no'),
'chat_diasexpira'=>array('valor'=>'16', 'autoload'=>'no'),
'examenes_exp'=>array('valor'=>'15', 'autoload'=>'no'),

// MODULO SOCIOS
'socios_estado'=>array('valor'=>'false', 'autoload'=>'si'),
'socios_ID'=>array('valor'=>'', 'autoload'=>'no'),
'socios_descripcion'=>array('valor'=>'', 'autoload'=>'no'),
'socios_responsable'=>array('valor'=>'', 'autoload'=>'no'),
			);

			if ($r['asamblea'] == 'true') {
				$param['acceso'] = array('valor'=>'votacion_borrador;ciudadanos:|sondeo;cargo:6|referendum;cargo:6|parlamento;cargo:6|kick;cargo:6 13|kick_quitar;cargo:6 13|foro_borrar;cargo:6 13|control_gobierno;cargo:6|control_sancion;:|control_grupos;cargo:6|control_cargos;cargo:6|examenes_decano;cargo:6|examenes_profesor;privado:|crear_partido;cargo:6|control_socios;cargo:6|api_borrador;ciudadanos:|control_docs;cargo:6', 'autoload'=>'si');

				sql("INSERT INTO cargos (pais, cargo_ID, asigna, nombre, nivel, elecciones, elecciones_electos, elecciones_cada, elecciones_durante, elecciones_votan) VALUES ('".$r['pais']."', '6', '0', 'Coordinador', '100', '".date('Y-m-d 20:00:00', time()+60*60*24*7)."', '7', '14', '2', 'ciudadanos')");
				sql("INSERT INTO examenes (pais, titulo, time, cargo_ID) VALUES ('".$r['pais']."', 'Coordinador', '".$date."', '6')");
				$cargo_primario = 6;
			} else {
				$param['acceso'] = array('valor'=>'votacion_borrador;ciudadanos:|sondeo;cargo:7|referendum;cargo:7|parlamento;cargo:7|kick;cargo:7 13|kick_quitar;cargo:7 13|foro_borrar;cargo:7 13|control_gobierno;cargo:7|control_sancion;:|control_grupos;cargo:7|control_cargos;cargo:7|examenes_decano;cargo:7|examenes_profesor;privado:|crear_partido;cargo:7|control_socios;cargo:7|api_borrador;ciudadanos:|control_docs;cargo:7', 'autoload'=>'si');

				sql("INSERT INTO cargos (pais, cargo_ID, asigna, nombre, nivel, elecciones, elecciones_electos, elecciones_cada, elecciones_durante, elecciones_votan) VALUES ('".$r['pais']."', '7', '0', 'Presidente', '100', '".date('Y-m-d 20:00:00', time()+60*60*24*7)."', '1', '14', '2', 'ciudadanos')");
				sql("INSERT INTO examenes (pais, titulo, time, cargo_ID) VALUES ('".$r['pais']."', 'Presidente', '".$date."', '7')");
				$cargo_primario = 7;
			}
			
			sql("INSERT INTO cargos (pais, cargo_ID, asigna, nombre, nivel) VALUES ('".$r['pais']."', '13', '".$cargo_primario."', 'Moderador', '50')");
			sql("INSERT INTO examenes (pais, titulo, time, cargo_ID) VALUES ('".$r['pais']."', 'Moderador', '".$date."', '13')");

			// PARAMETROS PRINCIPALES
			foreach ($param AS $dato => $valores) {
				sql("INSERT INTO config (pais, dato, valor, autoload) VALUES ('".$r['pais']."', '".$dato."', '".$valores['valor']."', '".$valores['autoload']."')");
			}


			// PARAMETROS DE ECONOMIA
			if ($r['ECONOMIA'] == 'true') {
				
				$param_economia = array(
'pols_inem'=>array('valor'=>'0', 'autoload'=>'no'),
'pols_frase'=>array('valor'=>'', 'autoload'=>'si'),
'online_ref'=>array('valor'=>'0', 'autoload'=>'no'),
'factor_propiedad'=>array('valor'=>'0', 'autoload'=>'no'),
'impuestos_minimo'=>array('valor'=>'0', 'autoload'=>'no'),
'impuestos'=>array('valor'=>'0', 'autoload'=>'no'),
'arancel_entrada'=>array('valor'=>'', 'autoload'=>'no'),
'arancel_salida'=>array('valor'=>'0', 'autoload'=>'no'),
'impuestos_empresa'=>array('valor'=>'0', 'autoload'=>'no'),
'pols_afiliacion'=>array('valor'=>'0', 'autoload'=>'no'),
'pols_fraseedit'=>array('valor'=>'', 'autoload'=>'si'),
'pols_empresa'=>array('valor'=>'0', 'autoload'=>'si'),
'pols_cuentas'=>array('valor'=>'0', 'autoload'=>'si'),
'pols_partido'=>array('valor'=>'1', 'autoload'=>'si'),
'pols_solar'=>array('valor'=>'0', 'autoload'=>'no'),
'pols_mensajetodos'=>array('valor'=>'300', 'autoload'=>'no'),
'pols_examen'=>array('valor'=>'0', 'autoload'=>'no'),
'pols_mensajeurgente'=>array('valor'=>'0', 'autoload'=>'no'),
'pols_crearchat'=>array('valor'=>'0', 'autoload'=>'no'),
				);
				foreach ($param_economia AS $dato => $valores) {
					sql("INSERT INTO config (pais, dato, valor, autoload) VALUES ('".$r['pais']."', '".$dato."', '".$valores['valor']."', '".$valores['autoload']."')");
				}

				// CUENTA BANCARIA DE GOBIERNO
				sql("INSERT INTO cuentas (pais, nombre, nivel, time, gobierno) VALUES ('".$r['pais']."', 'Gobierno', '98', '".$date."', 'true')");
			}
			
			// CREAR TABLAS DE FOROS (LAS UNICAS INDEPENDIENTES POR CADA PLATAFORMA)
			sql("CREATE TABLE `".strtolower($r['pais'])."_foros` (
  `ID` smallint(5) NOT NULL auto_increment,
  `subforo_ID` smallint(6) unsigned default NULL,
  `url` varchar(50) character set utf8 NOT NULL default '',
  `title` varchar(50) character set utf8 NOT NULL default '',
  `descripcion` varchar(255) character set utf8 NOT NULL default '',
  `acceso` tinyint(3) unsigned NOT NULL default '1',
  `time` smallint(3) NOT NULL default '1',
  `estado` enum('ok','eliminado') character set utf8 NOT NULL default 'ok',
  `acceso_msg` tinyint(3) unsigned NOT NULL default '1',
  `acceso_leer` varchar(900) character set utf8 NOT NULL default 'anonimos',
  `acceso_escribir` varchar(900) character set utf8 NOT NULL default 'ciudadanos_global',
  `acceso_escribir_msg` varchar(900) NOT NULL default 'ciudadanos_global',
  `acceso_cfg_leer` varchar(900) character set utf8 NOT NULL default '',
  `acceso_cfg_escribir` varchar(900) character set utf8 NOT NULL default '',
  `acceso_cfg_escribir_msg` varchar(900) NOT NULL default '',
  `limite` tinyint(3) unsigned NOT NULL default '8',
  PRIMARY KEY  (`ID`),
  UNIQUE KEY `url` (`url`),
  KEY `estado` (`estado`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=latin1");

sql("CREATE TABLE `".strtolower($r['pais'])."_foros_hilos` (
  `ID` mediumint(8) NOT NULL auto_increment,
  `sub_ID` smallint(5) NOT NULL default '0',
  `url` varchar(80) NOT NULL default '',
  `user_ID` mediumint(8) NOT NULL default '0',
  `title` varchar(80) NOT NULL default '',
  `time` datetime NOT NULL default '0000-00-00 00:00:00',
  `time_last` datetime NOT NULL default '0000-00-00 00:00:00',
  `text` text NOT NULL,
  `cargo` tinyint(3) NOT NULL default '0',
  `num` smallint(5) NOT NULL default '0',
  `estado` enum('ok','borrado') NOT NULL default 'ok',
  `votos` smallint(6) NOT NULL default '0',
  `votos_num` mediumint(9) unsigned NOT NULL default '0',
  PRIMARY KEY  (`ID`),
  UNIQUE KEY `url` (`url`),
  KEY `sub_ID` (`sub_ID`),
  KEY `time_last` (`time_last`),
  KEY `estado` (`estado`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=latin1");

sql("CREATE TABLE `".strtolower($r['pais'])."_foros_msg` (
  `ID` int(10) unsigned NOT NULL auto_increment,
  `hilo_ID` mediumint(8) NOT NULL default '0',
  `user_ID` mediumint(8) unsigned NOT NULL default '0',
  `time` datetime NOT NULL default '0000-00-00 00:00:00',
  `text` text NOT NULL,
  `cargo` tinyint(3) unsigned NOT NULL default '1',
  `estado` enum('ok','borrado') NOT NULL default 'ok',
  `time2` datetime NOT NULL default '0000-00-00 00:00:00',
  `votos` smallint(6) NOT NULL default '0',
  `votos_num` mediumint(8) NOT NULL default '0',
  PRIMARY KEY  (`ID`),
  KEY `foro_ID` (`hilo_ID`),
  KEY `time` (`time`),
  KEY `estado` (`estado`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=latin1");


			// Subforo General
			sql("INSERT INTO ".strtolower($r['pais'])."_foros (url, title, descripcion, acceso, time, estado, acceso_msg, acceso_leer, acceso_escribir, acceso_escribir_msg, acceso_cfg_leer, acceso_cfg_escribir, acceso_cfg_escribir_msg, limite) VALUES ('general', 'General', '', '1', '1', 'ok', '0', 'anonimos', 'ciudadanos_global', 'ciudadanos_global', '', '', '', '10')");

			// PREGUNTA DE EXAMEN GENERICA
			sql("INSERT INTO examenes_preg (pais, examen_ID, user_ID, time, pregunta, respuestas, tiempo) VALUES ('".$r['pais']."', '0', '0', '".$date."', 'Quieres ser candidato de este cargo', 'SI|NO', '15')");
			
			// CHAT PRINCIPAL
			sql("INSERT INTO chats (pais, estado, url, titulo, user_ID, fecha_creacion, fecha_last) VALUES ('".$r['pais']."', 'activo', '".strtolower($r['pais'])."', 'Plaza de ".$r['pais']."', '0', '".$date."', '".$date."')");

			// ESTADISTICAS DEL DIA 0
			sql("INSERT INTO stats (pais, time) VALUES ('".$r['pais']."', '".date('Y-m-d 20:00:00')."')");

			copy(RAIZ.'/img/banderas/default.png', RAIZ.'/img/banderas/'.$r['pais'].'.png');
		}

		redirect('/crear-plataforma.php?a=admin');
		// *** CREAR NUEVA PLATAFORMA ***
	}
	
	// PANEL ADMIN
	$txt .= '<table>
<tr>
<th></th>
<th>Hace</th>
<th>Nick</th>
<th>Estado</th>
<th>Nombre</th>
<th title="Participación">Part</th>
<th>Asam</th>
<th>Econ</th>
<th>Descripción</th>
</tr>';
	$result = sql("SELECT *, (SELECT nick FROM users WHERE ID = plataformas.user_ID LIMIT 1) AS nick FROM plataformas ORDER BY estado DESC");
	while($r = r($result)) { 
		$txt .= '<tr>
<td>'.($r['estado']=='pendiente'?boton('Crear', '/crear-plataforma.php?a=admin&b=aprobar&ID='.$r['ID'], '¿Estás seguro de crear esta plataforma?', 'red'):'').'</td>
<td nowrap>'.timer($r['time']).'</td>
<td><a href="http://15m.virtualpol.com/perfil/'.$r['nick'].'">'.$r['nick'].'</a></td>
<td><b>'.ucfirst($r['estado']).'</b></td>
<td><b style="font-size:20px;">'.$r['pais'].'</b></td>
<td align="right">'.$r['participacion'].'</td>
<td>'.$r['asamblea'].'</td>
<td>'.$r['economia'].'</td>
<td>'.$r['descripcion'].'</td>
</tr>';
	}
	$txt .= '</table>';




} else { // FORMULARIO AÑADIR PLATAFORMA

	$txt .= '

<p>VirtualPol es la primera red social democrática. Dentro de VirtualPol coexisten plataformas diferentes, independientes y soberanas. Este formulario es para solicitar la creación de una nueva.</p>

<form action="/crear-plataforma.php?a=accion&b=add" method="post">

<fieldset><legend>Solicitar nueva plataforma en VirtualPol</legend>

'.(isset($pol['user_ID'])?'':'<p style="color:red;">'.boton('Crear ciudadano', REGISTRAR, false, 'small blue').' Debes ser ciudadano para poder solicitar una nueva plataforma.</p>').'

<table>

<tr><td colspan="3">&nbsp;</td></tr>

<tr>
<td colspan="3">
<ul>
<li><u style="color:red;"><b>No</b> se aprobarán nuevas plataformas que supongan <b>una duplicación de otras ya existentes</b></u>. Debes saber que cada plataforma de VirtualPol puede albergar centenares de miles de participantes (creando multiples salas de chat, foros, documentos, votaciones... publicas y privadas). Esta condición es necesaria para concentrar la participación masiva, en lugar de dar pié a la atomización y fragmentación de la participación. Para que una plataforma funcione correctamente debe tener al menos 100 participantes activos para que compitan por los cargos y todo adquiera sentido.</li>
<li>Solo se creará una plataforma a grupos de personas preexistentes. Si estás solo y quieres iniciar un grupo puedes comenzar a crearlo dentro de cualquiera de las plataformas existentes.</li>
<li>Cada plataforma es gratuita pero consume recursos, por lo tanto debe existir una justificación para que sea creada, más allá del interés personal de una o pocas personas.</li>
<li>Cualquier plataforma podrá ser eliminada por inactividad si tiene menos de 30 usuarios activos.</li>
<li>Las plataformas serán ordenadas y priorizadas en función de su numero de ciudadanos inscritos.</li>
<li>Las siguientes opciones de configuración no podrán ser modificadas en el futuro sin aprobación de VirtualPol.</li>
<li><b>Cada plataforma es soberana</b> (es un principio de VirtualPol, ver principios en el <a href="/tos" target="_blank">TOS</a>, segundo apartado) y por lo tanto decide su propia gestión. Sin embargo la primera "legislatura" ostentará el poder el usuario que solicita la plataforma. Después el poder dependerá de unas elecciones automáticas y que -de ningún modo- se podrán detener u obstaculizar. Esto significa -explicitamente- que <b>el fundador inicial de la plataforma puede perder totalmente su control</b>, mediante principios democráticos.</li>
<li>Si -del modo que fuera- en una plataforma se rompe el principio "Democracia", cosa que tecnicamente es imposible, tendrá que ser intervenida por VirtualPol para restaurar de nuevo la democracia automática, de la forma menos intrusiva posible.</li>
</ul>
<input type="checkbox" name="condiciones_extra" value="true" required /> <b>He leído y aceptado estas condiciones adicionales</b>.<br /><br /><br />
</td>
</tr>

<tr>
<td align="right"><b>Nombre</b></td>
<td><input type="text" name="pais" value="" size="10" maxlength="10" required /></td>
<td>Nombre corto de la plataforma. Por ejemplo "15M" o "Hispania". Solo letras y numeros, sin espacios.</td>
</tr>

<tr><td colspan="3">&nbsp;</td></tr>

<tr>
<td align="right" valign="top"><b>Cargo primario</b></td>
<td colspan="2">
<input type="radio" name="asamblea" value="false" checked="checked" /> <b>Presidencial: Un presidente electo.</b><br />
Organización muy estable y operativa.<br />
<br />
<input type="radio" name="asamblea" value="true" /> <b>Parlamentario: Coordinadores electos (iguales entre sí).</b><br />
Organización menos estable y operativa, pero más representativo.<br />
<br />
<em>* El sistema permite establecer jerarquias completas de cargos y responsabilidades. Un organigrama completo y escalable. Incluso elecciones independientes para cada cargo. Sin embargo debe existir un cargo "primario" y electo, del que parte toda la responsabilidad. En cualquier caso siempre estará disponible una votación de tipo "ejecutiva" que -con el apoyo de la mayoría- el sistema puede destituir y reemplazar cualquier cargo.</em>
</td>
</tr>

<tr><td colspan="3">&nbsp;</td></tr>

<tr>
<td align="right" nowrap="nowrap"><b>¿Simulador de economía?</b></td>
<td>
<select name="economia">
<option value="false" selected="selected">Desactivado</option>
<option value="true">Activado</option>
</select>
</td>
<td></td>
</tr>

<tr><td colspan="3">&nbsp;</td></tr>

<tr>
<td align="right" nowrap="nowrap"><b>Previsión de participación</b></td>
<td><input type="text" name="participacion" value="50" style="text-align:right;" size="5" maxlength="5" /></td>
<td><b>Número de ciudadanos activos previstos tras 30 días</b>: Es importante que haya un potencial considerable. Una plataforma con solo 25 usuarios carece de sentido y es imposible que funcione, al no haber competencia para los cargos.</td>
</tr>

<tr><td colspan="3">&nbsp;</td></tr>


<tr>
<td align="right" valign="top"><b>Justificación</b></td>
<td colspan="2"><p>Razones y argumentos de porqué debe crearse esta plataforma en VirtualPol. Brevemente.</p>
<textarea name="descripcion" style="width:500px;height:200px;" required></textarea><br />
<em>* La aprobación o rechazo dependerá directamente de este paso.</em></td>
</tr>


<tr><td colspan="3">&nbsp;</td></tr>


<tr>
<td colspan="3">'.boton('Solicitar nueva plataforma', (isset($pol['user_ID'])?'submit':false), false, 'large orange').'</td>
</tr>


</table>



</fieldset>

</form>';





}


$txt_nav = array('/crear-plataforma.php'=>'Solicitar plataforma');
$txt_tab = array('/'=>'Ver plataformas existentes');
include('theme.php');
?>