<?php
/* The source code packaged with this file is Free Software, Copyright (C) 2008 by
** Javier González González <desarrollo AT virtualpol.com> <gonzomail AT gmail.com>
** It's licensed under the GNU GENERAL PUBLIC LICENSE v3 unless stated otherwise.
** You can get copies of the licenses here: http://www.gnu.org/licenses/gpl.html
** The source: http://www.virtualpol.com/codigo - TOS: http://www.virtualpol.com/TOS
** VirtualPol, The first Democratic Social Network - http://www.virtualpol.com
*/

include('inc-login.php');
include('parsedown/Parsedown.php');

if ($_GET['a']) {
	$parsedown = new Parsedown;
	$parsedown->setSafeMode(true);
	$parsedown->setBreaksEnabled(true);

	$result = mysql_query("SELECT * FROM docs WHERE url = '".$_GET['a']."' AND pais = '".PAIS."' LIMIT 1", $link);
	while($r = mysql_fetch_array($result)){
		
		$pad_ID = $r['pad_ID'];

		include('inc-functions-accion.php');

		if (($_GET['b'] == 'editar') AND ((nucleo_acceso($r['acceso_escribir'], $r['acceso_cfg_escribir'])) OR (nucleo_acceso($vp['acceso']['control_docs'])))) { 
			// EDITAR!

			foreach (nucleo_acceso('print') AS $at => $at_var) { 
				$txt_li['leer'] .= '<option value="'.$at.'"'.($at==$r['acceso_leer']?' selected="selected"':'').' />'.ucfirst(str_replace("_", " ", _($at))).'</option>';
			}
			foreach (nucleo_acceso('print') AS $at => $at_var) { 
				$txt_li['escribir'] .= '<option value="'.$at.'"'.($at==$r['acceso_escribir']?' selected="selected"':'').'>'.ucfirst(str_replace("_", " ", _($at))).'</option>';
			}

			
			pad('create', $pad_ID, $r['text']);

			$txt .= '
<form action="'.accion_url($pol['pais']).'a=editar-documento&ID='.$r['ID'].'" name="editar_documento" id="editar_documento" method="POST">
<input type="hidden" name="url" value="'.$r['url'].'"  />
<input type="hidden" name="doc_ID" value="'.$r['ID'].'"  />
<input type="hidden" name="html_doc" id="html_doc" value="'.$r['text'].'" />

<h1 class="quitar" style="margin-bottom:6px;"><a href="/doc">'._('Documento').'</a>: '._('Editar').'</a></h1>

<div id="doc_opciones" style="display:none;">
<table>

<tr>
<td align="right">'._('Categoría').':</td>
<td>'.form_select_cat('docs', $r['cat_ID']).'</td>
</tr>


<tr>
<td align="right"><b>'._('Acceso leer').'</b>:</td>
<td><select name="acceso_leer">'.$txt_li['leer'].'</select> <input type="text" name="acceso_cfg_leer" size="18" maxlength="900" id="acceso_cfg_leer_var" value="'.$r['acceso_cfg_leer'].'" /> '.ucfirst(verbalizar_acceso($r['acceso_leer'], $r['acceso_cfg_leer'])).'</td>
</tr>

<tr>
<td align="right"><b>'._('Acceso escribir').'</b>:</td>
<td><select name="acceso_escribir">'.$txt_li['escribir'].'</select> <input type="text" name="acceso_cfg_escribir" size="18" maxlength="900" id="acceso_cfg_escribir_var" value="'.$r['acceso_cfg_escribir'].'" /> '.ucfirst(verbalizar_acceso($r['acceso_escribir'], $r['acceso_cfg_escribir'])).'</td>
</tr>



<tr><td colspan="2" valign="top">

<span style="float:right;">
'.boton(_('Eliminar documento'), accion_url().'a=eliminar-documento&url='.$r['url'], '¿Estas convencido de que quieres ELIMINAR para siempre este Documento?', 'small red').'<br />
'.boton(_('Restaurar última publicación'), accion_url().'a=restaurar-documento&ID='.$r['ID'], '¿Estas seguro de RESTAURAR este documento?\n\nATENCION: SE PERDERA EL FORMATO, ADEMAS DE LOS CAMBIOS DESDE LA ULTIMA PUBLICACION.', 'small red').'
</span>

* '._('El texto del editor se guarda automáticamente como borrador en tiempo real. Para guardar estas opciones y hacer públicos los cambios hay que dar al botón "Publicar"').'.<br />
* <a href="/doc/'.$r['url'].'/presentacion" target="_blank">'._('Presentación').'</a> (HTML para <a href="https://github.com/bartaz/impress.js" target="_blank">impress.js</a>, <a href="https://github.com/bartaz/impress.js/blob/master/index.html" target="_blank">código de ejemplo</a>)</td>

</tr>


	

</table>
</div>

<div style="margin:5px 0;">
<input type="text" name="titulo" value="'.$r['title'].'" size="30" maxlength="50" style="font-size:22px;" /> &nbsp; 
<button onclick="$(\'#doc_opciones\').slideToggle(\'slow\');return false;">'._('Opciones').'</button> &nbsp; 
'.boton(_('Publicar'), 'submit', false, 'large blue').' <a href="/doc/'.$r['url'].'">'._('Última publicación hace').' <span class="timer" value="'.strtotime($r['time_last']).'"></span></a>.</div>
<iframe style="width:100%;height:850px;scrolling: none; border: none" id="document_frame" src="/editor_markdown.php">
</iframe>
</form>

'.pad('print', $pad_ID);
			$txt_nav = array('/doc'=>_('Documentos'), '/doc/'.$r['url']=>$r['title'], _('Editar'));
			$txt_tab['/doc/'.$r['url']] = _('Ver documento');
			$txt_tab['/doc/'.$r['url'].'/editar'] = _('Editar');

		} elseif ($_GET['b'] == 'presentacion') { //doc/documento-de-test/presentacion

			if (nucleo_acceso($r['acceso_leer'], $r['acceso_cfg_leer'])) {
				presentacion($r['title'], $r['text'], 'http://'.strtolower(PAIS).'.'.DOMAIN.'/doc/'.$r['url']);
			} else { $txt .= '<b style="color:red;">'._('No tienes acceso de lectura').'.</b>'; }

		} else { //doc/documento-de-test
			if ((nucleo_acceso($r['acceso_escribir'], $r['acceso_cfg_escribir'])) || (nucleo_acceso($vp['acceso']['control_docs']))) {
				$boton_editar = boton(_('Editar'), '/doc/'.$r['url'].'/editar');
				$txt_tab['/doc/'.$r['url'].'/editar'] = _('Editar');
			} else {
				$boton_editar = boton(_('Editar'), null);
			}

			if ($_GET['b'] == 'backup') { $r['text'] = $r['text_backup']; }

			if (substr($r['text'], 0, 8) == '&lt;div ') { $r['text'] = '<p style="font-size:25px;"><a href="/doc/'.$r['url'].'/presentacion"><b>'._('Ver presentación').'</b></a></p>'; }

			$txt .= '
<div>
<h1 style="font-size:28px;">'.$r['title'].'</h1>

<div id="doc_pad" style="min-height:250px;">
'.(nucleo_acceso($r['acceso_leer'], $r['acceso_cfg_leer'])||nucleo_acceso($vp['acceso']['control_docs'])?$parsedown->text($r['text']):'<b style="color:red;">'._('No tienes acceso de lectura').'.</b>').'
</div>

</div>


<fieldset><legend>'._('Info').'</legend>
'._('Creado hace').' '.timer($r['time']).'. '._('Última publicación hace').' '.timer($r['time_last']).', '._('versión').': '.$r['version'].'.<br />
'._('Pueden ver').': '.verbalizar_acceso($r['acceso_leer'], $r['acceso_cfg_leer']).'.<br />
'._('Pueden editar').': '.verbalizar_acceso($r['acceso_escribir'], $r['acceso_cfg_escribir']).'.
</fieldset>';
			$txt_nav = array('/doc'=>'Documentos', $r['title']);
			if (nucleo_acceso($r['acceso_escribir'], $r['acceso_cfg_escribir'])||nucleo_acceso($vp['acceso']['control_docs'])) { $txt_tab['/doc/'.$r['url'].'/editar'] = _('Editar'); }
		}
		$txt_title = $r['title'];
	}

} else { //docs

	$txt_title = _('Documentos');
	$txt_nav = array('/doc'=>_('Documentos'));
	$txt_tab = array('/form/crear-documento'=>_('Crear documento'));
	if (nucleo_acceso($vp['acceso']['control_docs'])) { $txt_tab['/control/gobierno/categorias'] = _('Editar categorías'); }

	$txt .= '<div id="docs">';

	$result = mysql_query("SELECT ID, nombre, tipo FROM cat WHERE pais = '".PAIS."' AND tipo = 'docs' ORDER BY orden ASC", $link);
	while($r = mysql_fetch_array($result)){

		// CAT
		$txt .= '<fieldset><legend>'.$r['nombre'].'</legend>

<table border="0" cellspacing="0" cellpadding="4" width="100%">
<tr>
<th></th>
<th colspan="2">'._('Publicado').'</th>
<th>'._('Lectura').'</th>
<th>'._('Escritura').'</th>
</tr>';
		
		$result2 = mysql_query("SELECT title, url, time, estado, time_last, acceso_leer, acceso_escribir, acceso_cfg_leer, acceso_cfg_escribir, version
FROM docs
WHERE estado = 'ok' AND cat_ID = '".$r['ID']."' AND pais = '".PAIS."'
ORDER BY time_last DESC", $link);
		while($r2 = mysql_fetch_array($result2)){

			if (nucleo_acceso($r2['acceso_leer'], $r2['acceso_cfg_leer'])) {
				$txt .= '<tr>
<td>'.(nucleo_acceso($r2['acceso_escribir'], $r2['acceso_cfg_escribir'])||nucleo_acceso($vp['acceso']['control_docs'])?' '.boton(_('Editar'), '/doc/'.$r2['url'].'/editar', false, 'small').' ':'').'<a href="/doc/'.$r2['url'].'"><b>'.$r2['title'].'</b></a></td>

<td width="80" align="right" nowrap="nowrap">'.timer($r2['time_last']).'</td>

<td width="50" align="right" class="gris" title="Versión">'.$r2['version'].'v</td>

<td width="135" valign="top" style="background:#5CB3FF;">'.($r2['acceso_cfg_leer']?'<acronym title="['.$r2['acceso_cfg_leer'].']">':'').ucfirst(_($r2['acceso_leer'])).($r2['acceso_cfg_leer']?'</acronym>':'').'</td>

<td width="135" valign="top" style="background:#F97E7B;">'.($r2['acceso_cfg_escribir']?'<acronym title="['.$r2['acceso_cfg_escribir'].']">':'').ucfirst(_($r2['acceso_escribir'])).($r2['acceso_cfg_escribir']?'</acronym>':'').'</td>

</tr>'."\n";
			}

		}
		$txt .= '</table></fieldset>';
	}
	$txt .= '</div>';
}

$txt .= mysql_error();

//THEME
$txt_menu = 'info';
include('theme.php');
?>