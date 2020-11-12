<?php # POL.VirtualPol.com — Copyright (c) 2008 Javier González González <gonzo@virtualpol.com> — MIT License 



$txt_menu = 'comu';


function reemplazos($t) { 
	return '<span class="rich">'.strip_tags($t, '<br>').'</span>'; 
}

function print_lateral($nick, $cargo_ID=false, $time, $siglas='', $user_ID='', $avatar='', $votos=0, $votos_num=false, $voto=false, $tipo='msg', $item_ID=0) {
	global $pol;
	if ($cargo_ID == 99) { $cargo = 'Extranjero'; }
	return '<table border="0" width="100%"><tr>
<td width="40" valign="top">'.($avatar=='true'?'<span>'.avatar($user_ID, 40).'</span>':'').'</td>
<td align="right" valign="top" nowrap="nowrap">
<b>'.($cargo_ID?'<img src="'.IMG.'cargos/'.$cargo_ID.'.gif" /> ':'').crear_link($nick).'</b><br />
<span class="min">'.timer($time).' '.$siglas.'</span> 
<span id="'.$tipo.$item_ID.'">'.confianza($votos, $votos_num).'</span>'.($pol['pais']==PAIS&&$item_ID!=0&&$user_ID!=$pol['user_ID']?'<br />
<span id="data_'.$tipo.$item_ID.'" class="votar" type="'.$tipo.'" name="'.$item_ID.'" value="'.$voto.'"></span>':'').'
</td></tr></table>';
}

function foro_enviar($subforo, $hilo=null, $edit=null, $citar=null) {
	global $pol, $link, $return_url, $vp;

	if (($pol['estado'] == 'ciudadano') OR ($pol['estado'] == 'extranjero')) {
		if ($edit) { //editar
			$return_url = 'foro/';
			if ($hilo) { //msg
				$result = mysql_query_old("SELECT text, cargo FROM ".SQL."foros_msg WHERE ID = '" . $hilo . "' AND estado = 'ok' AND user_ID = '" . $pol['user_ID'] . "' LIMIT 1", $link);
				while($r = mysqli_fetch_array($result)){ $edit_text = $r['text']; $edit_cargo = $r['cargo']; }
			} else { //hilo
				$result = mysql_query_old("SELECT sub_ID, text, cargo, title, user_ID, ID FROM ".SQL."foros_hilos WHERE ID = '" . $subforo . "' AND estado = 'ok' AND (user_ID = '".$pol['user_ID']."' OR 'true' = '".(nucleo_acceso($vp['acceso']['foro_borrar'])?'true':'false')."') LIMIT 1", $link);
				while($r = mysqli_fetch_array($result)){ $sub_ID = $r['sub_ID']; $edit_ID = $r['ID']; $edit_user_ID = $r['user_ID']; $edit_title = $r['title']; $edit_text = $r['text']; $edit_cargo = $r['cargo']; }
			}
			$edit_text = strip_tags($edit_text);
		}
		if ($citar != null) { //citar
			if ($citar>0) { //msg
				$result = mysql_query_old("SELECT text, user_ID FROM ".SQL."foros_msg WHERE ID = '" . $citar . "' AND estado = 'ok'  LIMIT 1", $link);
				while($r = mysqli_fetch_array($result)){ 
					$edit_text = $r['text']; 
					$user_ID = $r['user_ID'];
				}
			} 
			else {
				$result = mysql_query_old("SELECT text, user_ID FROM ".SQL."foros_hilos WHERE ID = '" . abs($citar) . "' AND estado = 'ok' LIMIT 1", $link);
				while($r = mysqli_fetch_array($result)){ 
					$edit_text = $r['text']; 
					$user_ID = $r['user_ID'];
				}
			}
			$result = mysql_query_old("SELECT nick FROM users WHERE ID = '" . $user_ID . "' LIMIT 1", $link);
			while($r = mysqli_fetch_array($result)){ 
				$edit_text = '[quote='.$r['nick'].'] '.$edit_text.' [/quote]'; 
			}
			
			$edit_text = strip_tags($edit_text);
			
		}

		if ($pol['nivel'] > 1) {
			$result = mysql_query_old("SELECT cargo_ID, 
(SELECT nombre FROM cargos WHERE pais = '".PAIS."' AND cargos_users.cargo_ID = cargo_ID LIMIT 1) AS nombre,
(SELECT nivel FROM cargos WHERE pais = '".PAIS."' AND cargos_users.cargo_ID = cargo_ID LIMIT 1) AS nivel
FROM cargos_users  
WHERE cargo = 'true' AND pais = '".PAIS."' AND user_ID = '".$pol['user_ID']."'
ORDER BY nivel DESC", $link);
			while($r = mysqli_fetch_array($result)){
				$select_cargos .= '<option value="'.$r['cargo_ID'].'"'.($edit_cargo==$r['cargo_ID']?' selected="selected"':'').'>'.$r['nombre'].'</option>'."\n";
			}
		}
		if ($pol['estado'] == 'extranjero') { $select_cargos = '<option value="99">Extranjero</option>'; } else { $select_cargos = '<option value="0">Ciudadano</option>' . $select_cargos; }

		if (!$hilo) { 
			if ($edit) { $get = 'editar'; } else { $get = 'hilo'; } 

			$html .= '<div id="enviar" class="redondeado">

<hr />


<fieldset><legend>Nuevo hilo</legend>
<script>
	function validarTitulo(){
		var titulo = $("#title").val();
		if (titulo.includes("<") || titulo.includes(">") || titulo.includes("/") ){
			$("#title")[0].setCustomValidity("El campo titulo contiene caracteres inválidos (<, > o /), por favor reviselo y pulse enviar");
			$("#title")[0].checkValidity();
			$("#title")[0].reportValidity();
			return false;
		}else{
			$("#title")[0].setCustomValidity("");
			return true;
		}

		
	}
</script>

<form action="/accion/foro/' . $get . '" method="post">
<input type="hidden" name="subforo" value="' . $subforo . '"  />
<input type="hidden" name="return_url" value="' . $return_url . '"  />';

			if ($edit) {
				$html .= '<p>Foro: <select name="sub_ID">';
				$result = mysql_query_old("SELECT ID, url, title, acceso_escribir, acceso_cfg_escribir FROM ".SQL."foros WHERE estado = 'ok' ORDER BY time ASC", $link);
				while($r = mysqli_fetch_array($result)){ 
					$html .= '<option value="'.$r['ID'].'"'.($r['ID']==$sub_ID?' selected="selected"':'').(nucleo_acceso($r['acceso_escribir'],$r['acceso_cfg_escribir'])?'':' disabled="disabled"').'>'.$r['title'].'</option>';
				}
				$html .= '</select></p>';
			}
			$html .= '
<p>Título:<br />
<input name="title" id="title" size="60" maxlength="80" type="text" value="'.str_replace('"', '&#34;', $edit_title).'" required /></p>

<p'.($edit&&$edit_user_ID!=$pol['user_ID']?' style="display:none;"':'').'>Mensaje:<br />
<textarea name="text" style="width:600px;height:260px;" required>'.$edit_text.'</textarea><br />
<span style="color:grey;font-size:12px;">Etiquetas: [b]...[/b] [em]...[/em] [quote]...[/quote] [img]url[/img] [youtube]url-youtube[/youtube], auto-enlaces.</span></p>

<p><button onclick="return validarTitulo();" class="large blue">Enviar</button> En calidad de: <select name="encalidad">' . $select_cargos . '
</select></p>
</form>

</fieldset>


'.($edit?'<hr /><p style="text-align:right;">'.boton('Eliminar hilo', '/accion/foro/eliminarhilo?ID='.$edit_ID, '¿Estás seguro de querer ELIMINAR este HILO DE FORMA IRREVOCABLE?').'</p>':'').'

</div>';
		} else {
			if ($edit) { $get = 'editar'; } else { $get = 'reply'; } 
			$html .= '<div id="enviar" class="redondeado">
<form action="/accion/foro/' . $get . '" method="post">
<input type="hidden" name="subforo" value="' . $subforo . '"  />
<input type="hidden" name="hilo" value="' . $hilo . '"  />
<input type="hidden" name="return_url" value="' . $return_url . '"  />

<hr />

<fieldset><legend>Mensaje en este hilo</legend>
<p>
<textarea name="text" style="width:570px;height:250px;" required>'.$edit_text.'</textarea><br />
<span style="color:grey;font-size:12px;">Etiquetas: [b]...[/b] [em]...[/em] [quote]...[/quote] [img]url[/img] [youtube]url-youtube[/youtube], auto-enlaces.</span></p>

<p>'.boton('Enviar', 'submit', false, 'blue large').' En calidad de: <select name="encalidad">' . $select_cargos . '
</select></p>
</fieldset>

</form>
</div>';
		}
		return $html;
	} else {
		return '<p class="azul"><b>Debes ser Ciudadano para participar, <a href="/registrar">regístrate aquí!</a></b></p>';
	}
}

