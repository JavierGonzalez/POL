<?php 
include('inc-login.php');

if ($_GET['a']) {

	$result = mysql_query("SELECT * FROM docs WHERE url = '".$_GET['a']."' AND pais = '".PAIS."' LIMIT 1", $link);
	while($r = mysql_fetch_array($result)){

		include('inc-functions-accion.php');

		if (($_GET['b'] == 'editar') AND ((nucleo_acceso($r['acceso_escribir'], $r['acceso_cfg_escribir'])) OR (nucleo_acceso($vp['acceso']['control_gobierno'])))) { 
			// EDITAR!

			foreach (nucleo_acceso('print') AS $at => $at_var) { 
				$txt_li['leer'] .= '<option value="'.$at.'"'.($at==$r['acceso_leer']?' selected="selected"':'').' />'.ucfirst(str_replace("_", " ", $at)).'</option>';
			}
			foreach (nucleo_acceso('print') AS $at => $at_var) { 
				$txt_li['escribir'] .= '<option value="'.$at.'"'.($at==$r['acceso_escribir']?' selected="selected"':'').'>'.ucfirst(str_replace("_", " ", $at)).'</option>';
			}

			
			pad('create', $r['ID'], $r['text']);

			$txt .= '
<form action="http://'.strtolower($pol['pais']).'.'.DOMAIN.'/accion.php?a=editar-documento&ID='.$r['ID'].'" method="POST">
<input type="hidden" name="url" value="'.$r['url'].'"  />
<input type="hidden" name="doc_ID" value="'.$r['ID'].'"  />

<h1 class="quitar" style="margin-bottom:6px;"><a href="/doc">Documento</a>: Editar</a></h1>

<div id="doc_opciones" style="display:none;">
<table border="0" cellpadding="9">
<tr>

<td valign="bottom">Categoría:<br />'.form_select_cat('docs', $r['cat_ID']).'</td>

<td valign="bottom"><b>Acceso leer:</b><br />
<select name="acceso_leer">'.$txt_li['leer'].'</select><br />
<input type="text" name="acceso_cfg_leer" size="18" maxlength="900" id="acceso_cfg_leer_var" value="'.$r['acceso_cfg_leer'].'" />
</td>

<td valign="bottom"><b>Acceso escribir:</b><br />
<select name="acceso_escribir">'.$txt_li['escribir'].'</select><br />
<input type="text" name="acceso_cfg_escribir" size="18" maxlength="900" id="acceso_cfg_escribir_var" value="'.$r['acceso_cfg_escribir'].'" />
</td>

</tr>

<tr><td colspan="2" valign="top">* El texto del editor se guarda automáticamente como borrador en tiempo real. Para guardar estas opciones y hacer públicos los cambios hay que dar al botón "Publicar".<br /><br />
* <a href="/doc/'.$r['url'].'/presentacion" target="_blank"><b>Presentación</b></a> (Funciona con HTML para <a href="https://github.com/bartaz/impress.js" target="_blank">impress.js</a>, <a href="https://github.com/bartaz/impress.js/blob/master/index.html" target="_blank">código de ejemplo</a>)</td>

<td align="right" valign="top">
'.boton('Restaurar última publicación', '/accion.php?a=restaurar-documento&ID='.$r['ID'], '¿Estas seguro de RESTAURAR este documento?\n\nATENCION: SE PERDERA EL FORMATO, ADEMAS DE LOS CAMBIOS DESDE LA ULTIMA PUBLICACION.', 'small').'<br />
'.boton('ELIMINAR DOCUMENTO', '/accion.php?a=eliminar-documento&url='.$r['url'], '¿Estas convencido de que quieres ELIMINAR para siempre este Documento?', 'small').'</td>

</tr>


	

</table>
</div>

<div style="margin:5px 0;">
<input type="text" name="titulo" value="'.$r['title'].'" size="30" maxlength="50" style="font-size:22px;" /> &nbsp; 
<button onclick="$(\'#doc_opciones\').slideToggle(\'slow\');return false;" style="font-size:16px;color:#666;">Opciones</button> &nbsp; 
<input type="submit" value="Publicar" style="font-size:22px;" /> <a href="/doc/'.$r['url'].'">Última publicación hace <span class="timer" value="'.strtotime($r['time_last']).'"></span></a>.</div>

</form>

'.pad('print', $r['ID']);
			$txt_nav = array('/doc'=>'Documentos', '/doc/'.$r['url']=>$r['title'], 'Editar');
			$txt_tab['/doc/'.$r['url']] = 'Ver documento';
			$txt_tab['/doc/'.$r['url'].'/editar'] = 'Editar';

		} elseif ($_GET['b'] == 'presentacion') { //doc/documento-de-test/presentacion

			if (nucleo_acceso($r['acceso_leer'], $r['acceso_cfg_leer'])) {
				presentacion($r['title'], $r['text'], 'http://'.strtolower(PAIS).'.'.DOMAIN.'/doc/'.$r['url']);
			} else { $txt .= '<b style="color:red;">No tienes acceso de lectura.</b>'; }

		} else { //doc/documento-de-test
			if ((nucleo_acceso($r['acceso_escribir'], $r['acceso_cfg_escribir'])) || (nucleo_acceso($vp['acceso']['control_gobierno']))) {
				$boton_editar = boton('Editar', '/doc/'.$r['url'].'/editar');
				$txt_tab['/doc/'.$r['url'].'/editar'] = 'Editar';
			} else {
				$boton_editar = boton('Editar', null);
			}

			if ($_GET['b'] == 'backup') { $r['text'] = $r['text_backup']; }

			if (strpos($r['text'], '&lt;/div&gt;')) { $r['text'] = '<p style="font-size:25px;"><a href="/doc/'.$r['url'].'/presentacion"><b>Ver presentación</b></a></p>'; }

			$txt .= '
<div>
<h1 style="font-size:28px;">'.$r['title'].' </h1>

<div id="doc_pad">
'.(nucleo_acceso($r['acceso_leer'], $r['acceso_cfg_leer'])||nucleo_acceso($vp['acceso']['control_gobierno'])?$r['text']:'<b style="color:red;">No tienes acceso de lectura.</b>').'
</div>

</div>


<fieldset><legend>Info</legend>
Creado hace '.timer($r['time']).'. Última publicación hace '.timer($r['time_last']).', versión: '.$r['version'].'.<br />
Pueden ver: '.verbalizar_acceso($r['acceso_leer'], $r['acceso_cfg_leer']).'.<br />
Pueden editar: '.verbalizar_acceso($r['acceso_escribir'], $r['acceso_cfg_escribir']).'.
</fieldset>';
			$txt_nav = array('/doc'=>'Documentos', $r['title']);
			if (nucleo_acceso($r['acceso_escribir'], $r['acceso_cfg_escribir'])||nucleo_acceso($vp['acceso']['control_gobierno'])) { $txt_tab['/doc/'.$r['url'].'/editar'] = 'Editar'; }
		}
		$txt_title = $r['title'];
	}



} else { //docs

	$txt_title = 'Documentos';
	$txt_nav = array('/doc'=>'Documentos');
	$txt_tab = array('/form/crear-documento'=>'Crear documento');

	$txt .= '<div id="docs">';

	$result = mysql_query("SELECT ID, nombre, tipo FROM cat WHERE pais = '".PAIS."' AND tipo = 'docs' ORDER BY orden ASC", $link);
	while($r = mysql_fetch_array($result)){

		// CAT
		$txt .= '<fieldset><legend>'.$r['nombre'].'</legend>

<table border="0" cellspacing="0" cellpadding="4" class="pol_table" width="100%">
<tr>
<th></th>
<th>Lectura</th>
<th>Escritura</th>
<th align="right">Publicado</th>
</tr>';
		
		$result2 = mysql_query("SELECT title, url, time, estado, time_last, acceso_leer, acceso_escribir, acceso_cfg_leer, acceso_cfg_escribir
FROM docs
WHERE estado = 'ok' AND cat_ID = '".$r['ID']."' AND pais = '".PAIS."'
ORDER BY title ASC", $link);
		while($r2 = mysql_fetch_array($result2)){

			if (nucleo_acceso($r2['acceso_leer'], $r2['acceso_cfg_leer'])) {
				$txt .= '<tr>
<td>'.(nucleo_acceso($r2['acceso_escribir'], $r2['acceso_cfg_escribir'])||nucleo_acceso($vp['acceso']['control_gobierno'])?' '.boton('Editar', '/doc/'.$r2['url'].'/editar', false, 'small').' ':'').'<a href="/doc/'.$r2['url'].'">'.$r2['title'].'</a></td>

<td width="90" valign="top" style="background:#5CB3FF;">'.($r2['acceso_cfg_leer']?'<acronym title="['.$r2['acceso_cfg_leer'].']">':'').ucfirst($r2['acceso_leer']).($r2['acceso_cfg_leer']?'</acronym>':'').'</td>

<td width="90" valign="top" style="background:#F97E7B;">'.($r2['acceso_cfg_escribir']?'<acronym title="['.$r2['acceso_cfg_escribir'].']">':'').ucfirst($r2['acceso_escribir']).($r2['acceso_cfg_escribir']?'</acronym>':'').'</td>

<td width="80" align="right" nowrap="nowrap">'.timer($r2['time_last']).'</td>
</tr>'."\n";
			}

		}
		$txt .= '</table></fieldset>';
	}
	$txt .= '';
}


//THEME
$txt_menu = 'info';
include('theme.php');
?>