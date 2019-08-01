<?php
/* The source code packaged with this file is Free Software, Copyright (C) 2008 by
** Javier González González <desarrollo AT virtualpol.com> <gonzomail AT gmail.com>
** It's licensed under the GNU GENERAL PUBLIC LICENSE v3 unless stated otherwise.
** You can get copies of the licenses here: http://www.gnu.org/licenses/gpl.html
** The source: http://www.virtualpol.com/codigo - TOS: http://www.virtualpol.com/TOS
** VirtualPol, The first Democratic Social Network - http://www.virtualpol.com
*/

include('inc-login.php');


/*

HAY QUE REEMPLAZAR Y ELIMINAR ESTE TROZO DE CODIGO CUANTO ANTES. 

ES UN FRACASO ESPERPENTICO. UN HORROR.

*/
include('inc-functions-accion.php');

function polform($action, $pol_form, $submit='Enviar', $submit_disable=false) {
	global $pol, $link;

	$f .= '<div class="pol_form">
<form action="/accion.php?a=' . $action . '" method="post">
<input type="hidden" name="user_ID" value="' . $pol['user_ID'] . '"  />
<ol>
';

	if ($pol_form) {
		foreach($pol_form as $v) {
			if (!$v['size']) { $v['size'] = '30'; }
			if (!$v['maxlenght']) { $v['maxlength'] = '255'; }

			switch ($v['type']) {
								
				case 'hidden':
					$f .= '<input type="hidden" name="' . $v['name'] . '" value="' . $v['value'] . '"  />' . "\n";
					
					break;

				case 'textrico':
					$f .= '<li><b>' . $v['nombre'] . ':</b><br />';
					//include('inc-functions-accion.php');
					$f .= editor_enriquecido($v['name']) . '</li>' . "\n";
					
					break;


				case 'text':
					$f .= '<li><b>' . $v['nombre'] . ':</b> ' . $v['desc'] . '<br /><input type="' . $v['type'] . '" name="' . $v['name'] . '" size="' . $v['size'] . '" maxlength="' . $v['maxlegth'] . '" /></li>' . "\n";
					break;

				case 'select_partidos':
					$f .= '<li><b>'._('Partidos').':</b><br /><select name="partido"><option value="0">'._('Ninguno').'</option>';
					
					$result = mysql_query("SELECT siglas, ID FROM partidos WHERE pais = '".PAIS."' AND estado = 'ok' ORDER BY siglas ASC", $link);
					while($row = mysql_fetch_array($result)){
						if ($v['partido'] == strtolower($row['siglas'])) { $selected = ' selected="selected"'; } else { $selected = '';  }
						$f .= '<option value="' . $row['ID'] . '"' . $selected . '>' . $row['siglas'] . '</option>';
					}

					$f .= '</select></li>' . "\n";
					break;

				case 'select_nivel':
					$f .= '<li><b>'._('Nivel de acceso').':</b><br />';
				
					$f .= '<select name="nivel"><option value="1">&nbsp;1 &nbsp; '._('Ciudadano').'</option>';
					if ($pol['nivel'] > 1) {
						$result = sql("SELECT nombre, nivel FROM cargos WHERE pais = '".PAIS."' AND asigna != '-1' AND nivel <= '".$pol['nivel']."' ORDER BY nivel ASC", $link);
						while($row = r($result)){
							if ($nivel_select == $row['nivel']) { $selected = ' selected="selected"'; } else { $selected = ''; }
							$f .= '<option value="' . $row['nivel'] . '"' . $selected . '>' . $row['nivel'] . ' &nbsp; ' . $row['nombre'] . '</option>' . "\n";
						}
					}
					$f .= '</select></li>' . "\n";
					break;

				case 'select_cat':
					$f .= '<li><b>'._('Categoría').':</b><br />' . form_select_cat('docs') . '</li>' . "\n";
					break;


				case 'selectexpire':
					$f .= '<li><b>'._('Duración').':</b>.<br />
<select name="expire">
<option value="60">60 '._('segundos').'</option>
<option value="120">2 '._('minutos').'</option>
<option value="300">5 '._('minutos').'</option>
<option value="600">10 '._('minutos').'</option>
<option value="900">15 '._('minutos').'</option>
<option value="1800">30 '._('minutos').'</option>
<option value="3600">60 '._('minutos').'</option>
<option value="18000">5 '._('horas').'</option>
<option value="86400">24 '._('horas').'</option>
<option value="259200">3 '._('días').'</option>
<option value="518400">6 '._('días').'</option>
<option value="777600">9 '._('días').'</option>
</select></li>' . "\n";
					break;



			}
		}
	}
	if ($submit_disable == true) { $submit_disable = ' disabled="disabled"'; }
	$f .= '<li><input type="submit" value="' . $submit . '"' . $submit_disable . ' /></li></ol></form></div>';

	return $f;
}








switch ($_GET['a']) {

case 'crear-documento':

	$txt .= '<p>Formulario para crear un nuevo documento en '.PAIS.'.</p>';

	$pol_form = array(
	array('type'=>'select_cat'),
	array('type'=>'hidden', 'name'=>'acceso_leer', 'value'=>'ciudadanos'),
	array('type'=>'hidden', 'name'=>'acceso_escribir', 'value'=>'privado'),
	array('type'=>'hidden', 'name'=>'acceso_cfg_leer', 'value'=>''),
	array('type'=>'hidden', 'name'=>'acceso_cfg_escribir', 'value'=>strtolower($pol['nick'])),
	array('type'=>'text', 'name'=>'title', 'size'=>'60', 'maxlegth'=>'200', 'nombre'=>'Título', 'desc'=>'Frase &uacute;nica a modo de titular del documento.'),
	array('type'=>'hidden', 'name'=>'text', 'value'=>''),
	);
	$txt .= polform($_GET['a'], $pol_form, _('Crear documento'));


	break;

case 'solicitar-ciudadania': redirect(REGISTRAR); break;


case 'afiliarse':

	$txt .= '<p>'._('Afiliación').' '._('partidos').':</p>';

	$pol_form = array(
	array('type'=>'select_partidos', 'partido'=>$_GET['b']),
	);
	if ($pol['config']['elecciones_estado'] == 'elecciones') { $submit_disable = true; } else { $submit_disable = false; }
	$txt .= polform($_GET['a'], $pol_form, 'Afiliarse', $submit_disable);


	break;

case 'crear-partido':

	$partido = "";
	$result = sql("SELECT ID, siglas, nombre FROM partidos WHERE pais = '".PAIS."' AND ID_presidente = '".$pol['user_ID']."'");
	while($r = r($result)){ $partido = crear_link($r['siglas'], 'partido');}
	if ($partido == ""){
		$txt .= '<h2>'._('Crear partido').':</h2>';
		$pol_form = array(
		array('type'=>'text', 'name'=>'siglas', 'value'=>'', 'size'=>'6', 'maxlegth'=>'10', 'nombre'=>'Siglas', 'desc'=>'Escribe entre 2 y 10 letras may&uacute;sculas, guion permitido.'),
		array('type'=>'text', 'name'=>'nombre', 'value'=>'', 'size'=>'', 'maxlegth'=>'40', 'nombre'=>'Nombre', 'desc'=>'Frase a modo de nombre que concuerda con las siglas anteriormente dadas.'),
		array('type'=>'textrico', 'name'=>'descripcion', 'size'=>'10', 'nombre'=>'Introducci&oacute;n'),
		);
		
		$txt .= polform($_GET['a'], $pol_form, _('Crear partido'));
	}
	
	$txt .= '<p><br/>'.('Ya eres presidente de un partido: ').$partido.'</p>';
	$txt .= '<a href="/partidos/"><b>'._('Volver').'</b></a>';
	break;


default: redirect('http://'.HOST.'/');
}


//THEME
include('theme.php');
?>