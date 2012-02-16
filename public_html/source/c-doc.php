<?php 
include('inc-login.php');

if ($_GET['a']) {

	$result = mysql_query("SELECT * FROM docs WHERE url = '".$_GET['a']."' AND pais = '".PAIS."' LIMIT 1", $link);
	while($r = mysql_fetch_array($result)){

		include('inc-functions-accion.php');

		if (($_GET['b'] == 'editar') AND (nucleo_acceso($r['acceso_escribir'], $r['acceso_cfg_escribir']))) { 
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

<span style="float:right;margin-top:-12px;">
<button onclick="$(\'#doc_opciones\').slideToggle(\'slow\');return false;" style="font-size:16px;color:#666;">Opciones</button>
</span>

<h1 style="margin-bottom:6px;"><a href="/doc/">Documento</a>: Editar</a></h1>

<div id="doc_opciones" style="display:none;">
<table border="0" cellpadding="9">
<tr>

<td valign="bottom">Categor&iacute;a:<br />'.form_select_cat('docs', $r['cat_ID']).'</td>

<td valign="bottom"><b>Acceso leer:</b><br />
<select name="acceso_leer">'.$txt_li['leer'].'</select><br />
<input type="text" name="acceso_cfg_leer" size="18" maxlength="900" id="acceso_cfg_leer_var" value="'.$r['acceso_cfg_leer'].'" />
</td>

<td valign="bottom"><b>Acceso escribir:</b><br />
<select name="acceso_escribir">'.$txt_li['escribir'].'</select><br />
<input type="text" name="acceso_cfg_escribir" size="18" maxlength="900" id="acceso_cfg_escribir_var" value="'.$r['acceso_cfg_escribir'].'" />
</td>

</tr>

<tr><td colspan="2" valign="top">* El texto del editor se guarda autom&aacute;ticamente como borrador en tiempo real. Para guardar estas opciones y hacer p&uacute;blicos los cambios hay que dar al bot&oacute;n "Publicar".<br /><br />
* <a href="/doc/'.$r['url'].'/presentacion" target="_blank"><b>Presentación</b></a> (Funciona con HTML para <a href="https://github.com/bartaz/impress.js" target="_blank">impress.js</a>, <a href="https://github.com/bartaz/impress.js/blob/master/index.html" target="_blank">código de ejemplo</a>) - <a href="/doc/'.$r['url'].'/backup/" target="_blank">Backup</a> (enero 2012).</td>

<td align="right" valign="top">
'.boton('Restaurar &uacute;ltima publicaci&oacute;n', '/accion.php?a=restaurar-documento&ID='.$r['ID'], '&iquest;Estas seguro de RESTAURAR este documento?\n\nATENCION: SE PERDERA EL FORMATO, ADEMAS DE LOS CAMBIOS DESDE LA ULTIMA PUBLICACION.').'<br />
'.boton('ELIMINAR DOCUMENTO', '/accion.php?a=eliminar-documento&url='.$r['url'], '&iquest;Estas convencido de que quieres ELIMINAR para siempre este Documento?').'</td>

</tr>


	

</table>
</div>

<div>
<input type="text" name="titulo" value="'.$r['title'].'" size="30" maxlength="50" style="font-size:22px;" /> &nbsp; 
<input type="submit" value="Publicar" style="font-size:22px;" /> <a href="/doc/'.$r['url'].'/">&Uacute;ltima publicaci&oacute;n hace <span class="timer" value="'.strtotime($r['time_last']).'"></span></a>.</div>

</form>

'.pad('print', $r['ID']);

		} elseif ($_GET['b'] == 'presentacion') { //doc/documento-de-test/presentacion

			if (nucleo_acceso($r['acceso_leer'], $r['acceso_cfg_leer'])) {


$r['text'] .= '
&lt;div style="position: fixed; bottom: 10px; left: 10px;"&gt;
&lt;a href="https://twitter.com/share" class="twitter-share-button" data-text="Presentación http://'.strtolower(PAIS).'.'.DOMAIN.'/doc/'.$r['url'].'/presentacion VirtualPol" data-lang="es" data-size="large" data-related="VirtualPol"&gt;Twittear&lt;/a&gt;
&lt;script&gt;!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0];if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src="//platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");&lt;/script&gt;
&lt;/div&gt;

&lt;a href="http://'.strtolower(PAIS).'.'.DOMAIN.'/doc/'.$r['url'].'"&gt;&lt;img style="position: absolute; top: -3px; left: -3px; border: 0; border-bottom-right-radius:12px; -moz-border-radius-bottomright:12px; -webkit-border-bottom-right-radius:12px; opacity:0.5;filter:alpha(opacity=50)" src="'.IMG.'logo-virtualpol-1.gif" alt="VirtualPol"&gt;&lt;/a&gt;';



				presentacion($r['title'], $r['text']);
			} else { $txt .= '<b style="color:red;">No tienes acceso de lectura.</b>'; }

		} else { //doc/documento-de-test
			$boton_editar = boton('Editar', (nucleo_acceso($r['acceso_escribir'], $r['acceso_cfg_escribir'])?'/doc/'.$r['url'].'/editar/':null));

			if ($_GET['b'] == 'backup') { $r['text'] = $r['text_backup']; }

			if (strpos($r['text'], '&lt;/div&gt;')) { $r['text'] = '<p style="font-size:25px;"><a href="/doc/'.$r['url'].'/presentacion"><b>Ver presentación</b></a></p>'; }

			$txt .= '<h1><a href="/doc/">Documento</a>: '.$boton_editar.'</h1>


<div style="color:#555;">
<h1 style="color:#444;text-align:center;font-size:28px;">'.$r['title'].' </h1>

<div id="doc_pad">
'.(nucleo_acceso($r['acceso_leer'], $r['acceso_cfg_leer'])?$r['text']:'<b style="color:red;">No tienes acceso de lectura.</b>').'
</div>

</div>

<hr />'; 

			$txt .= '<div style="color:#777;">
'.$boton_editar.' Creado hace '.timer($r['time']).'. Última publicación hace '.timer($r['time_last']).', versión: '.$r['version'].'.<br />
Pueden ver: '.verbalizar_acceso($r['acceso_leer'], $r['acceso_cfg_leer']).'.<br />
Pueden editar: '.verbalizar_acceso($r['acceso_escribir'], $r['acceso_cfg_escribir']).'.
</div>';
		}

		$txt_title = $r['title'];
	}


} else { //docs/


	$txt_title = 'Documentos';
	$txt .= '<h1><img src="'.IMG.'documentos/doc.gif" alt="Documento" width="20" height="22" /> Documentos: &nbsp; '.boton('Crear Documento', '/form/crear-documento/').'</h1>

<br />

<div id="docs">';



	$result = mysql_query("SELECT ID, nombre, tipo FROM ".SQL."cat WHERE tipo = 'docs' ORDER BY orden ASC", $link);
	while($r = mysql_fetch_array($result)){

		// CAT
		$txt .= '<div class="amarillo"><b style="font-size:20px;padding:15px;color:green;">'.$r['nombre'].'</b></div>';
		
		$txt .= '<table border="0" cellspacing="0" cellpadding="4" class="pol_table" width="100%">
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
<td>'.(nucleo_acceso($r2['acceso_escribir'], $r2['acceso_cfg_escribir'])?' '.boton('Editar', '/doc/'.$r2['url'].'/editar/', 'm'):'').'<a href="/doc/'.$r2['url'].'/">'.$r2['title'].'</a></td>

<td width="90" valign="top" style="background:#5CB3FF;">'.($r2['acceso_cfg_leer']?'<acronym title="['.$r2['acceso_cfg_leer'].']">':'').ucfirst($r2['acceso_leer']).($r2['acceso_cfg_leer']?'</acronym>':'').'</td>

<td width="90" valign="top" style="background:#F97E7B;">'.($r2['acceso_cfg_escribir']?'<acronym title="['.$r2['acceso_cfg_escribir'].']">':'').ucfirst($r2['acceso_escribir']).($r2['acceso_cfg_escribir']?'</acronym>':'').'</td>

<td width="80" align="right" nowrap="nowrap"><span class="timer" value="'.strtotime($r2['time_last']).'"></span></td>
</tr>'."\n";
			}

		}
		$txt .= '</table><br />';
	}


	$txt .= '</div>

<p>' . boton('Crear Documento', '/form/crear-documento/') . '</p>';

}



//THEME

include('theme.php');
?>
