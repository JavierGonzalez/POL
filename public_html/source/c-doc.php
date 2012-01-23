<?php 
include('inc-login.php');

if ($_GET['a']) {

	$result = mysql_query("SELECT * FROM docs WHERE url = '".trim($_GET['a'])."' AND pais = '".PAIS."' LIMIT 1", $link);
	while($r = mysql_fetch_array($result)){

		if (($_GET['b'] == 'editar') AND (nucleo_acceso($r['acceso_escribir'], $r['acceso_cfg_escribir']))) { //EDITAR!

			$text = $r['text'];

			$confirm_salir = ' onClick="if (!confirm(\'&iquest;Seguro que quieres salir? No se guardar&aacute;n los cambios.\')) { return false; }"';
			include('inc-functions-accion.php');
			$txt .= '<form action="http://'.strtolower($pol['pais']).'.'.DOMAIN.'/accion.php?a=editar-documento&ID='.$r['ID'].'" method="post">
<input type="hidden" name="url" value="' . $r['url'] . '"  />

<h1><img src="'.IMG.'documentos/doc-edit.gif" alt="Editar Documento" /> <a href="/doc/">Documento</a>: <input type="text" name="titulo" value="' . $r['title'] . '" size="60" /></h1>

<p>Categor&iacute;a:<br />
' . form_select_cat('docs', $r['cat_ID']) . '</p>';


		foreach (nucleo_acceso('print') AS $at => $at_var) { 
			$txt_li['leer'] .= '<input type="radio" name="acceso_leer" value="'.$at.'"'.($at==$r['acceso_leer']?' checked="checked"':'').' onclick="$(\'#acceso_cfg_leer_var\').val(\''.$at_var.'\');" /> '.ucfirst(str_replace("_", " ", $at)).'<br />';
		}
		foreach (nucleo_acceso('print') AS $at => $at_var) { 
			$txt_li['escribir'] .= '<input type="radio" name="acceso_escribir" value="'.$at.'"'.($at==$r['acceso_escribir']?' checked="checked"':'').' onclick="$(\'#acceso_cfg_escribir_var\').val(\''.$at_var.'\');" /> '.ucfirst(str_replace("_", " ", $at)).'<br />';
		}

		$txt .= '<table border="0" cellpadding="9">
<tr>
<td><b>Acceso leer:</b><br />
'.$txt_li['leer'].' <input type="text" name="acceso_cfg_leer" size="18" maxlength="500" id="acceso_cfg_leer_var" value="'.$r['acceso_cfg_leer'].'" /></td>

<td><b>Acceso escribir:</b><br />
'.$txt_li['escribir'].' <input type="text" name="acceso_cfg_escribir" size="18" maxlength="500" id="acceso_cfg_escribir_var" value="'.$r['acceso_cfg_escribir'].'" /></td>
</table>

<p>' . editor_enriquecido('text', $text) . '</p>

<p><input type="submit" value="Guardar" /></form></p>';

		} else { 

			$txt .= '<h1><img src="'.IMG.'documentos/doc.gif" alt="Documento" /> <a href="/doc/">Documento</a>: ' . $r['title'] . '</h1><div style="text-align:justify;margin:20px;">'.(nucleo_acceso($r['acceso_leer'], $r['acceso_cfg_leer'])?$r['text']:'<b style="color:red;">No tienes acceso de lectura.</b>').'</div><br /><br /><hr style="width:100%;" />'; 

			if (nucleo_acceso($r['acceso_escribir'], $r['acceso_cfg_escribir'])) {
				$txt .= '<span style="float:right;"><form><input type="button" value="Eliminar" onClick="if (!confirm(\'&iquest;Estas convencido de que quieres ELIMINAR para siempre este Documento?\')) { return false; } else { window.location.href=\'/accion.php?a=eliminar-documento&url=' . $r['url'] . '\'; }"></form></span>';
			}
			$txt .= '<span><form><input type="button" value="Editar" onclick="window.location.href=\'/doc/'.$r['url'].'/editar/\'"'.(nucleo_acceso($r['acceso_escribir'], $r['acceso_cfg_escribir'])?'':' disabled="disabled"').'> Creado el <em>' . explodear(' ',$r['time'], 0) . '</em>, &uacute;ltima edici&oacute;n hace <em>' . duracion(time() - strtotime($r['time_last'])) . '</em>.</form></span>';
		}

		$txt_title = $r['title'];
	}


} else { //docs/


	$txt_title = 'Documentos';
	$txt .= '<h1><img src="'.IMG.'documentos/doc.gif" alt="Documento" /> Documentos:</h1>

<p>' . boton('Crear Documento', '/form/crear-documento/') . '</p>

<div id="docs">';



	$result = mysql_query("SELECT ID, nombre, tipo FROM ".SQL."cat WHERE tipo = 'docs' ORDER BY orden ASC", $link);
	while($r = mysql_fetch_array($result)){

		// CAT
		$txt .= '<div class="amarillo"><b style="font-size:20px;padding:15px;color:green;">' . $r['nombre'] . '</b></div>';
		
		$txt .= '<table border="0" cellspacing="0" cellpadding="4" class="pol_table" width="100%">
<tr>
<th></th>
<th>Lectura</th>
<th>Escritura</th>
<th align="right">Edici&oacute;n</th>
</tr>';
		
		$result2 = mysql_query("SELECT title, url, time, estado, time_last, acceso_leer, acceso_escribir, acceso_cfg_leer, acceso_cfg_escribir
FROM docs
WHERE estado = 'ok' AND cat_ID = '".$r['ID']."' AND pais = '".PAIS."'
ORDER BY title ASC", $link);
		while($r2 = mysql_fetch_array($result2)){

			$txt .= '<tr>
<td width="100%">'.(nucleo_acceso($r2['acceso_escribir'], $r2['acceso_cfg_escribir'])?'<div style="float:right;"><a href="/doc/'.$r2['url'].'/editar/">Editar</a></div>':'').''.(nucleo_acceso($r2['acceso_leer'], $r2['acceso_cfg_leer'])?'<a href="/doc/'.$r2['url'].'/"><b>'.$r2['title'].'</b></a>':'<a href="/doc/'.$r2['url'].'/">'.$r2['title'].'</a>').'</td>

<td valign="top" style="background:#5CB3FF;">'.($r2['acceso_cfg_leer']?'<acronym title="['.$r2['acceso_cfg_leer'].']">':'').ucfirst($r2['acceso_leer']).($r2['acceso_cfg_leer']?'</acronym>':'').'</td>

<td valign="top" style="background:#F97E7B;">'.($r2['acceso_cfg_escribir']?'<acronym title="['.$r2['acceso_cfg_escribir'].']">':'').ucfirst($r2['acceso_escribir']).($r2['acceso_cfg_escribir']?'</acronym>':'').'</td>

<td align="right" nowrap="nowrap"><span class="timer" value="'.strtotime($r2['time_last']).'"></span></td>
</tr>'."\n";

		}
		$txt .= '</table><br />';
	}


	$txt .= '</div>

<p>' . boton('Crear Documento', '/form/crear-documento/') . '</p>';

}



//THEME

include('theme.php');
?>
