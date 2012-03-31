<?php


// ### NUCLEO ACCESO 3.0
function nucleo_acceso($tipo, $valor='') {
	global $_SESSION;
	$rt = false;
	if (is_array($tipo)) { $valor = $tipo[1]; $tipo = $tipo[0]; }
	switch ($tipo) {
		case 'internet': case 'anonimos': if ($_SESSION['pol']['estado'] != 'expulsado') { $rt = true; } break;
		case 'ciudadanos_global': if ((isset($_SESSION['pol']['user_ID'])) AND ($_SESSION['pol']['estado'] == 'ciudadano')) { $rt = true; } break;
		case 'ciudadanos': if (($_SESSION['pol']['estado'] == 'ciudadano') && (($_SESSION['pol']['pais'] == PAIS) || (in_array(strtolower($_SESSION['pol']['pais']), explode(' ', strtolower($valor)))))) { $rt = true; } break;
		case 'excluir': if ((isset($_SESSION['pol']['nick'])) AND (!in_array(strtolower($_SESSION['pol']['nick']), explode(' ', strtolower($valor))))) { $rt = true; } break;
		case 'privado': if ((isset($_SESSION['pol']['nick'])) AND (in_array(strtolower($_SESSION['pol']['nick']), explode(' ', strtolower($valor))))) { $rt = true; } break;
		case 'afiliado': if (($_SESSION['pol']['pais'] == PAIS) AND ($_SESSION['pol']['partido_afiliado'] == $valor)) { $rt = true; } break;
		case 'confianza': if ($_SESSION['pol']['confianza'] >= $valor) { $rt = true; } break;
		case 'nivel': if (($_SESSION['pol']['pais'] == PAIS) AND ($_SESSION['pol']['nivel'] >= $valor)) { $rt = true; } break;
		case 'cargo': if (($_SESSION['pol']['pais'] == PAIS) AND (count(array_intersect(explode(' ', $_SESSION['pol']['cargos']), explode(' ', $valor))) > 0)) { $rt = true; } break;
		case 'grupos': if (($_SESSION['pol']['pais'] == PAIS) AND (count(array_intersect(explode(' ', $_SESSION['pol']['grupos']), explode(' ', $valor))) > 0)) { $rt = true; } break;
		case 'examenes': if (($_SESSION['pol']['pais'] == PAIS) AND (count(array_intersect(explode(' ', $_SESSION['pol']['examenes']), explode(' ', $valor))) > 0)) { $rt = true; } break;
		case 'monedas': if ($_SESSION['pol']['pols'] >= $valor) { $rt = true; } break;
		case 'autentificados': if ($_SESSION['pol']['dnie'] == 'true') { $rt = true; } break;
		case 'supervisores_censo': if ($_SESSION['pol']['SC'] == 'true') { $rt = true; } break;
		case 'antiguedad': if (($_SESSION['pol']['fecha_registro']) AND (strtotime($_SESSION['pol']['fecha_registro']) < (time() - ($valor*86400)))) { $rt = true; } break;
		case 'print': 
			if (ASAMBLEA) {	return array('privado'=>'Nick ...', 'excluir'=>'Nick ...', 'afiliado'=>'partido_ID', 'confianza'=>'0', 'cargo'=>'cargo_ID ...', 'grupos'=>'grupo_ID ...', 'nivel'=>'1', 'antiguedad'=>'365', 'autentificados'=>'', 'supervisores_censo'=>'', 'ciudadanos'=>'', 'ciudadanos_global'=>'', 'anonimos'=>''); } 
			else { return array('privado'=>'Nick ...', 'excluir'=>'Nick ...', 'afiliado'=>'partido_ID', 'confianza'=>'0', 'cargo'=>'cargo_ID ...', 'grupos'=>'grupo_ID ...', 'nivel'=>'1', 'antiguedad'=>'365', 'monedas'=>'0', 'autentificados'=>'', 'supervisores_censo'=>'', 'ciudadanos'=>'', 'ciudadanos_global'=>'', 'anonimos'=>''); }
		exit;
	}
	return $rt;
}

function verbalizar_acceso($tipo, $valor='') {
	if (is_array($tipo)) { $valor = $tipo[1]; $tipo = $tipo[0]; }
	switch ($tipo) { // ¿Quien tiene acceso?
		case 'internet': case 'anonimos': $t = 'todo el mundo'; break;
		case 'ciudadanos_global': $t = 'todos los ciudadanos de VirtualPol'; break;
		case 'ciudadanos': $t = ($valor==''?'todos los ciudadanos de esta plataforma':'todos los ciudadanos de esta plataforma y '.$valor); break;
		case 'excluir': $t = 'todos los ciudadanos excepto: '.$valor; break;
		case 'privado': $t = 'los ciudadanos: '.$valor; break;
		case 'afiliado': $t = 'ciudadanos afiliados al <span title="ID: '.$valor.'">partido</span> (<a href="/partidos">Ver partidos</a>)'; break;
		case 'confianza': $t = 'ciudadanos con confianza mayor o igual a '.confianza($valor).' (<a href="/censo/confianza">Ver confianza</a>)'; break;
		case 'nivel': $t = 'ciudadanos con nivel mayor o igual a '.$valor.' (<a href="/cargos">Ver cargos</a>)'; break;
		case 'cargo': $t = 'ciudadanos con cargo: '.$valor.' (<a href="/cargos">Ver cargos</a>)'; break;
		case 'grupos': $t = 'ciudadanos afiliados al grupo: '.$valor.' (<a href="/grupos">Ver grupos</a>)'; break;
		case 'examenes': $t = 'ciudadanos con los siguientes exámenes aprobados: '.$valor.' (<a href="/examenes">Ver exámenes</a>)'; break;
		case 'monedas': $t = 'ciudadanos con al menos '.$valor.' monedas'; break;
		case 'autentificados': $t = 'ciudadanos autentificados'; break;
		case 'supervisores_censo': $t = 'Supervisores del Censo'; break;
		case 'antiguedad': $t = 'ciudadanos con al menos '.$valor.' dias de antig&uuml;edad';  break;
	}
	return $t;
}

function notificacion($user_ID, $texto='', $url='', $emisor='sistema') {
	global $pol, $link;
	switch ($user_ID) {

		case 'print':
			if (isset($pol['user_ID'])) {
				$t = ''; $total_num = 0;
				
				// NOTIFICACION ELECCIONES
				if (($pol['config']['elecciones_estado'] == 'elecciones') AND ($pol['estado'] == 'ciudadano')) {
					$result = mysql_query("SELECT ID FROM ".SQL."elecciones WHERE user_ID = '" . $pol['user_ID'] . "' LIMIT 1", $link);
					while($r = mysql_fetch_array($result)){ $havotado = $r['ID']; }
					if (!isset($havotado)) { $nuevos_num++; }
					$total_num++;
					$elecciones_quedan = (strtotime($pol['config']['elecciones_inicio']) + $pol['config']['elecciones_duracion']);
					if (!isset($havotado)) {
						$t .= '<li><a href="/elecciones/votar" class="noti-nuevo">&iexcl;Elecciones en curso!<span class="md">Quedan '.timer($elecciones_quedan, true).'</span></a></li>';
					}
				}

				// NOTIFICACION VOTACIONES
				$pol['config']['info_consultas'] = 0;
				$result = mysql_query("SELECT v.ID, pais, pregunta, acceso_votar, acceso_cfg_votar, acceso_ver, acceso_cfg_ver 
				FROM votacion `v`
				LEFT OUTER JOIN votacion_votos `vv` ON v.ID = vv.ref_ID AND vv.user_ID = '".$pol['user_ID']."'
				WHERE v.estado = 'ok' AND (v.pais = '".PAIS."' OR acceso_votar IN ('supervisores_censo', 'privado')) AND vv.ID IS null", $link);
				while($r = mysql_fetch_array($result)) {
					if ((nucleo_acceso($r['acceso_votar'], $r['acceso_cfg_votar'])) AND (nucleo_acceso($r['acceso_ver'], $r['acceso_cfg_ver']))) { 
						$pol['config']['info_consultas']++;
						$nuevos_num++;
						$total_num++;
						$t .= '<li><a href="http://'.$r['pais'].'.'.DOMAIN.'/votacion/'.$r['ID'].'" class="noti-nuevo">Votación: '.$r['pregunta'].'</a></li>';
					}
				}

				// NOTIFICACIONES
				$result = mysql_query("SELECT noti_ID, visto, texto, url, MAX(time) AS time_max, COUNT(*) AS num FROM notificaciones WHERE user_ID = '".$pol['user_ID']."' GROUP BY visto, texto ORDER BY visto DESC, time_max DESC LIMIT 7", $link);
				while($r = mysql_fetch_array($result)) {
					$total_num += $r['num'];
					if ($r['visto'] == 'false') { $nuevos_num += $r['num']; }
					$t .= '<li><a href="'.($r['visto']=='false'?'/?noti='.$r['noti_ID']:$r['url']).'"'.($r['visto']=='false'?' class="noti-nuevo"':'').(substr($r['url'], 0, 4)=='http'?' target="_blank"':'').'>'.$r['texto'].($r['num']>1?'<span class="md">'.$r['num'].'</span>':'').'</a></li>';
				}

			} else { $t = '<li><a href="'.REGISTRAR.'?p='.PAIS.'" class="noti-nuevo">Primer paso: Crea tu ciudadano</a></li>'; $total_num = 1; $nuevos_num = 1; }
			global $txt_elec_time;
			return '<li id="menu-noti"'.($nuevos_num!=0?' class="menu-sel"':'').'><a href="/hacer">Notificaciones<span class="md">'.$nuevos_num.'</span></a><ul><li style="border-bottom:1px dotted #DDD;"><a href="/elecciones">'.(isset($txt_elec_time)?'Elecciones en <b>'.$txt_elec_time.'</b>, ':'').'proceso '.(time()>strtotime(date('Y-m-d 20:00:00'))?'hace':'en').' <b>'.timer(date('Y-m-d 20:00:00')).'</b></a></li>'.$t.($total_num==0?'<li>No hay notificaciones</li>':'').'<li style="text-align:right;"><a href="/hacer"><b>¿Qué hacer?</b></a></li></ul></li>'.($nuevos!=0?'':'<script type="text/javascript">p_scroll = true;</script>');
			break;


		case 'visto': 
			$result = mysql_query("SELECT noti_ID, visto, texto, url FROM notificaciones WHERE noti_ID = '".$texto."' LIMIT 1", $link);
			while($r = mysql_fetch_array($result)) {
				if ($r['visto'] == 'false') {
					mysql_query("UPDATE notificaciones SET visto = 'true' WHERE visto = 'false' AND user_ID = '".$pol['user_ID']."' AND texto = '".$r['texto']."'", $link); 
				}
				redirect($r['url']);
			}
			break;

		default: 
			mysql_query("INSERT INTO notificaciones (user_ID, texto, url, emisor) VALUES ('".$user_ID."', '".$texto."', '".$url."', '".$emisor."')", $link);
			return true;
	}
}

function escape($a, $escape=true, $html=true) {
	// INYECCION SQL
	$a = nl2br($a);
	$a = str_replace('\'', '&#39;', $a);
	$a = str_replace('"', '&quot;', $a);
	if ($escape == true) { $a = mysql_real_escape_string($a); }
	
	// XSS
	if ($html == false) { $a = strip_tags($a); }
	$js_filter = 'javascript vbscript expression applet xml blink style script embed object iframe frame frameset ilayer bgsound onabort onactivate onafterprint onafterupdate onbeforeactivate onbeforecopy onbeforecut onbeforedeactivate onbeforeeditfocus onbeforepaste onbeforeprint onbeforeunload onbeforeupdate onblur onbounce oncellchange onchange onclick oncontextmenu oncontrolselect oncopy oncut ondataavailable ondatasetchanged ondatasetcomplete ondblclick ondeactivate ondrag ondragend ondragenter ondragleave ondragover ondragstart ondrop onerror onerrorupdate onfilterchange onfinish onfocus onfocusin onfocusout onhelp onkeydown onkeypress onkeyup onlayoutcomplete onload onlosecapture onmousedown onmouseenter onmouseleave onmousemove onmouseout onmouseover onmouseup onmousewheel onmove onmoveend onmovestart onpaste onpropertychange onreadystatechange onreset onresize onresizeend onresizestart onrowenter onrowexit onrowsdelete onrowsinserted onscroll onselect onselectionchange onselectstart onstart onstop onsubmit onunload';
	$a = str_replace(explode(' ', $js_filter), 'nojs', $a);

	return $a;
}



function pass_key($t, $type='sha') {
	switch ($type) {
		case 'md5': return hash('md5', $t); break;
		default: return hash('sha256', CLAVE_SHA.$t);
	}
}

function timer($t, $es_time=false) {
	if ($es_time == true) { return '<span class="timer" value="'.$t.'"></span>'; } 
	else { return '<span class="timer" value="'.strtotime($t).'" title="'.$t.'"></span>'; } 
}

function ocultar_IP($IP, $tipo='IP') { 
	// devuelve el host o IP indicado cortando alguno de sus datos, para proteger la privacidad 
	if ($tipo == 'IP') {
		$trozos = explode('.', long2ip($IP));
		return $trozos[0].'.'.$trozos[1].'.*';
	} elseif ($tipo == 'host') {
		$host = '';
		$hosts = explode('.', $IP);
		if (strlen($hosts[count($hosts)-3]) > 3) { 
			$host = $hosts[count($hosts)-3].'.'.$hosts[count($hosts)-2].'.'.$hosts[count($hosts)-1]; 
		}
		return '*.'.$host;
	}
}

function error($txt='Acción no permitida o erronea') {
	redirect('http://'.HOST.'/?error='.base64_encode($txt));
}

function redirect($url, $r301=true) {
	global $link;
	if ($r301) { header('HTTP/1.1 301 Moved Permanently'); } 
	header('Location: '.$url); 
	mysql_close($link);
	exit;
}

function get_supervisores_del_censo() {
	global $link;
	$result = mysql_query("SELECT ID, nick FROM users WHERE SC = 'true'", $link);
	while($r = mysql_fetch_array($result)){ $supervisores_del_censo[$r['ID']] = $r['nick']; }
	return $supervisores_del_censo; // Devuelve un array con los Supervisores del Censo activos. Formato: $array[user_ID] = nick;
}

function duracion($t) {
	if ($t > 5356800) { $d = round($t / 2626560) . ' meses'; }
	elseif ($t > 129600) { $d = round($t / 86400) . ' días'; }
	elseif ($t > 86400) { $d = '1 día'; }
	elseif ($t > 7200) { $d = round($t / 3600) . ' horas'; }
	elseif ($t > 3600) { $d = '1 hora'; }
	elseif ($t > 60) { $d = round($t / 60) . ' min'; }
	else { $d = $t . ' seg'; }
	return $d;
}

function crear_link($a, $tipo='nick', $estado='', $pais='') {
	switch ($tipo) {
		case 'nick': 
			if ($a == 'VirtualPol') {
				return 'VirtualPol';
			} else if ($a) { 
				$bg = '';
				if ($pais) {
					global $vp;
					$bg = ' style="background:'.$vp['bg'][$pais].';"';
					$add_class .= ' redondeado';
				}
				if (($estado) && ($estado != 'ciudadano')) { 
					return '<a href="/perfil/'.$a.'" class="nick'.$add_class.' '.$estado.'"'.$bg.'>'.$a.'</a>';
				} else {
					return '<a href="/perfil/'.$a.'" class="nick'.$add_class.'"'.$bg.'>'.$a.'</a>';
				}
			} else { 
				return '<span title="Usuario expirado">&dagger;</span>'; 
			} 
			break;
		case 'partido': if ($a) { return '<a href="/partidos/'.strtolower($a).'">'.$a.'</a>'; } else { return 'Ninguno'; } break;
		case 'documento': return '<a href="/doc/'.$a.'">/doc/'.$a.'</a>'; break;
	}
}

function num($num, $dec=0) { return number_format(round($num, $dec), $dec, ',', '.'); }

function explodear($pat, $str, $num) { $exp = explode($pat, $str); return $exp[$num]; }
function implodear($pat, $str, $num) { $exp = implode($pat, $str); return $exp[$num]; }
function entre($num, $min, $max) { if ((is_numeric($num)) AND ($num >= $min) AND ($num <= $max)) { return true; } else { return false; } }

function boton($texto, $url=false, $confirm=false, $size=false, $pols='', $html_extra=false) {
	if (($pols=='') OR (ECONOMIA == false)) {
		return '<button'.($url==false?' disabled="disabled"':' onClick="'.($confirm!=false?'if(!confirm(\''.$confirm.'\')){return false;}':'').($url!='submit'?'window.location.href=\''.$url.'\';return false;':'').'"').($size!=false?' class="'.$size.'"':'').($html_extra!=false?$html_extra:'').'>'.$texto.'</button>';
	} else {
		global $pol;
		return '<span class="amarillo"><input type="submit" value="'.$texto.'"'.($pol['pols']<$pols?' disabled="disabled"':' onClick="'.($confirm!=false?'if(!confirm(\''.$confirm.'\')){return false;}':'').'window.location.href=\''.$url.'\';"').' class="large blue" />'.(ECONOMIA?' &nbsp; '.pols($pols).' '.MONEDA.'':'').($html_extra!=false?$html_extra:'').'</span>';
	}
}


function form_select_nivel($nivel_select='') {
	global $pol, $link;
	$f .= '<select name="nivel"><option value="1">&nbsp;1 &nbsp; Ciudadano</option>';
	if ($pol['nivel'] > 1) {
		$result = mysql_query("
SELECT nombre, nivel 
FROM cargos 
WHERE pais = '".PAIS."' AND asigna != '-1' AND nivel <= '" . $pol['nivel'] . "' 
ORDER BY nivel ASC", $link);
		while($row = mysql_fetch_array($result)){
			if ($nivel_select == $row['nivel']) { $selected = ' selected="selected"'; } else { $selected = ''; }
			$f .= '<option value="' . $row['nivel'] . '"' . $selected . '>' . $row['nivel'] . ' &nbsp; ' . $row['nombre'] . '</option>' . "\n";
		}
	}
	$f .= '</select>';
	return $f;
}

function form_select_cat($tipo='docs', $cat_now='') {
	global $pol, $link;
	$f .= '<select name="cat">';
	$result = mysql_query("
SELECT ID, nombre, nivel
FROM cat
WHERE pais = '".PAIS."' AND tipo = '" . $tipo . "'
ORDER BY orden ASC", $link);
	while($row = mysql_fetch_array($result)){
		if ($cat_now == $row['ID']) { 
			$selected = ' selected="selected"'; 
		} elseif ($pol['nivel'] < $row['nivel']) {
			$selected = ' disabled="disabled"'; 
			$row['nombre'] = $row['nombre'] . ' (Nivel: ' . $row['nivel'] . ')';
		} else { 
			$selected = ''; 
		}
		$f .= '<option value="' . $row['ID'] . '"' . $selected . '>' . $row['nombre'] . '</option>' . "\n";
	}
	$f .= '</select>';
	return $f;

}

function cargos() {
	global $pol, $link; 
	$result = mysql_query("SELECT cargo_ID FROM cargos_users WHERE cargo = 'true' AND user_ID = '" . $pol['user_ID'] . "'", $link);
	while($row = mysql_fetch_array($result)) { $cargos[$row['cargo_ID']] = true; }
	return $cargos;
}

// FUNCION MALA. REEMPLAZAR URGENTE.
function paginacion($type, $url, $ID, $num_ahora=null, $num_total=null, $num='10') {
	global $link, $p_limit, $p_paginas, $p_init;
	if (!$num_total) {
		switch ($type) {
			case 'subforo': 
				$result = mysql_fetch_row(mysql_query("SELECT COUNT(ID) FROM ".SQL."foros_hilos WHERE ID = '" . $ID . "'", $link));
				$num_total = $result[0];
				break;
			case 'eventos': 
				$result = mysql_fetch_row(mysql_query("SELECT COUNT(ID) FROM log WHERE pais = '".PAIS."'", $link));
				$num_total = $result[0];
				break;
		}
	}
	if ($num_total == 1) {
		$num_paginas = 1;
	} else {
		$num_paginas = ceil(($num_total - 1) / $num);
	}
	if (($type == 'censo') OR ($type == 'eventos')) {
		if (!$num_ahora) { $num_ahora = 1; }
		for ($i=1;$i <= $num_paginas;$i++) {
			if ($i == 1) { $el_url = $url;
			} else { $el_url = $url . $i . '/'; }
			if ($i == $num_ahora) {
				$html .= '<span class="amarillo">&nbsp;<b><a href="' . $el_url . '">' . $i . '</a></b>&nbsp;</span> ';
			} else {
				$html .= '<a href="' . $el_url . '">' . $i . '</a> ';
			}
		}
		$p_limit = (($num_ahora - 1) * $num) . ', ' . $num;
		$p_init = (($num_ahora - 1) * $num);
	} else {
	
		if ($num_total > ($num_paginas * $num)) { $num_paginas++; }
		if (!$num_ahora) { $num_ahora = $num_paginas; }

		for ($i=1;$i <= $num_paginas;$i++) {
			if ($i == $num_paginas) { $el_url = $url; } 
			else { $el_url = $url . $i . '/'; }

			if ($i == $num_ahora) {
				$html .= '<span class="amarillo">&nbsp;<b><a href="' . $el_url . '">' . $i . '</a></b>&nbsp;</span> ';
			} else {
				$html .= '<a href="' . $el_url . '">' . $i . '</a> ';
			}
		}
		$p_init = (($num_ahora - 1) * $num);
		if ($p_init < 0) { $p_init = 0; }
		$p_limit = $p_init . ', ' . $num;
	}
	$p_paginas = '<span style="font-size:20px;">' . $html . '</span>';
}

function chart_data($values, $maxValue=false) {
	if ($values) { $maxValue = max($values); }
	$simpleEncoding = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
	for ($i = 0; $i < count($values); $i++) {
		$currentValue = $values[$i];
		if ($currentValue == 0) {
			$chartData.=substr($simpleEncoding,61*0,1);
		} elseif ($currentValue > -1) { $chartData.=substr($simpleEncoding,61*($currentValue/$maxValue),1); 
		} else { $chartData.='_'; }
	}
	return $chartData;
}

function pols($pols) {
	$pols = number_format($pols, 0, ',', '.');
	if ($pols < 0) { return '<span class="pn">' . $pols . '</span>'; }
	else { return '<span class="pp">' . $pols . '</span>'; }
}

function confianza($num, $votos_num=false) {
	if ($num >= 10) { return '<span title="'.$votos_num.' votos" class="vcc">+'.$num.'</span>'; }
	elseif ($num >= 0) { return '<span title="'.$votos_num.' votos" class="vc">+'.$num.'</span>'; } 
	elseif ($num > -10) { return '<span title="'.$votos_num.' votos" class="vcn">'.$num.'</span>'; }
	else { return '<span title="'.$votos_num.' votos" class="vcnn">'.$num.'</span>'; }
}

function direccion_IP($tipo='') {
	$IP = $_SERVER['REMOTE_ADDR'];
	if ($tipo == 'longip') { $IP = ip2long($IP); }
	return $IP;
}

function avatar($user_ID, $size='') {
	if ($size) { $extra = '_' . $size; } else { $extra = ''; }
	return '<img src="'.IMG.'a/' . $user_ID . $extra . '.jpg" alt="' . $user_ID . '"'.($size!=''?' width="'.$size.'" height="'.$size.'" class="redondeado"':'').' />'; 
}

?>