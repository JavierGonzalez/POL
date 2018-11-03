<?php
/* The source code packaged with this file is Free Software, Copyright (C) 2008 by
** Javier González González <desarrollo AT virtualpol.com> <gonzomail AT gmail.com>
** It's licensed under the GNU GENERAL PUBLIC LICENSE v3 unless stated otherwise.
** You can get copies of the licenses here: http://www.gnu.org/licenses/gpl.html
** The source: http://www.virtualpol.com/codigo - TOS: http://www.virtualpol.com/TOS
** VirtualPol, The first Democratic Social Network - http://www.virtualpol.com
*/


// MySQL micro-framework v0.2
function sql($q,$l=null) {
	global $link; 
	if($l===true){$rr=mysql_query($q,$link);while($r=mysql_fetch_row($rr)){return $r[0];}} 
	else{return mysql_query($q,($l===null?$link:$l));}
}
function r($q) {return mysql_fetch_assoc($q);}


// ### NUCLEO ACCESO 3.1
function nucleo_acceso($tipo, $valor='', $pais=false) {
	global $_SESSION;
	$valor = trim($valor);
	$rt = false;
	if ($pais == false) { $pais = PAIS; }
	if (is_array($tipo)) { $valor = $tipo[1]; $tipo = $tipo[0]; }
	elseif (stristr($tipo, '|')) { $valor = explodear('|', $tipo, 1); $tipo = explodear('|', $tipo, 0); }
	switch ($tipo) {
		case 'internet': case 'anonimos': $rt = true; break;
		case 'ciudadanos_global': if ((isset($_SESSION['pol']['user_ID'])) AND ($_SESSION['pol']['estado'] == 'ciudadano')) { $rt = true; } break;
		case 'ciudadanos': if (($_SESSION['pol']['estado'] == 'ciudadano') && (($_SESSION['pol']['pais'] == $pais) || (in_array(strtolower($_SESSION['pol']['pais']), explode(' ', strtolower($valor)))))) { $rt = true; } break;
		case 'excluir': if ((isset($_SESSION['pol']['nick'])) AND (!in_array(strtolower($_SESSION['pol']['nick']), explode(' ', strtolower($valor))))) { $rt = true; } break;
		case 'privado': if ((isset($_SESSION['pol']['nick'])) AND (in_array(strtolower($_SESSION['pol']['nick']), explode(' ', strtolower($valor))))) { $rt = true; } break;
		case 'afiliado': if (($_SESSION['pol']['pais'] == $pais) AND ($_SESSION['pol']['partido_afiliado'] == $valor)) { $rt = true; } break;
		case 'confianza': if (($_SESSION['pol']['confianza'] >= $valor)) { $rt = true; } break;
		case 'nivel': if (($_SESSION['pol']['pais'] == $pais) AND ($_SESSION['pol']['nivel'] >= $valor)) { $rt = true; } break;
		case 'cargo': if (($_SESSION['pol']['pais'] == $pais) AND (count(array_intersect(explode(' ', $_SESSION['pol']['cargos']), explode(' ', $valor))) > 0)) { $rt = true; } break;
		case 'grupos': if (($_SESSION['pol']['pais'] == $pais) AND (count(array_intersect(explode(' ', $_SESSION['pol']['grupos']), explode(' ', $valor))) > 0)) { $rt = true; } break;
		case 'examenes': if (($_SESSION['pol']['pais'] == $pais) AND (count(array_intersect(explode(' ', $_SESSION['pol']['examenes']), explode(' ', $valor))) > 0)) { $rt = true; } break;
		case 'monedas': if ($_SESSION['pol']['pols'] >= $valor) { $rt = true; } break;
		case 'socios': if (($_SESSION['pol']['pais'] == $pais) AND ($_SESSION['pol']['socio'] == 'true')) { $rt = true; } break;
		case 'autentificados': if ($_SESSION['pol']['dnie'] == 'true') { $rt = true; } break;
		case 'supervisores_censo': if ($_SESSION['pol']['SC'] == 'true') { $rt = true; } break;
		case 'antiguedad': if ((isset($_SESSION['pol']['fecha_registro'])) AND (strtotime($_SESSION['pol']['fecha_registro']) < (time() - ($valor*86400)))) { $rt = true; } break;
		case 'print': 
			if (ASAMBLEA) {	return array('privado'=>'Nick ...', 'excluir'=>'Nick ...', 'afiliado'=>'partido_ID', 'confianza'=>'0', 'cargo'=>'cargo_ID ...', 'grupos'=>'grupo_ID ...', 'nivel'=>'1', 'antiguedad'=>'365', 'socios'=>'', 'autentificados'=>'', 'supervisores_censo'=>'', 'ciudadanos'=>'', 'ciudadanos_global'=>'', 'anonimos'=>''); } 
			else { return array('privado'=>'Nick ...', 'excluir'=>'Nick ...', 'afiliado'=>'partido_ID', 'confianza'=>'0', 'cargo'=>'cargo_ID ...', 'grupos'=>'grupo_ID ...', 'nivel'=>'1', 'antiguedad'=>'365', 'monedas'=>'0', 'socios'=>'', 'autentificados'=>'', 'supervisores_censo'=>'', 'ciudadanos'=>'', 'ciudadanos_global'=>'', 'anonimos'=>''); }
			exit;
	}
	if (in_array($_SESSION['pol']['estado'], array('kickeado', 'expulsado'))) { $rt = false; }
	return $rt;
}

function verbalizar_acceso($tipo, $valor='') {
	$valor = trim($valor);
	if (is_array($tipo)) { $valor = $tipo[1]; $tipo = $tipo[0]; }
	elseif (stristr($tipo, '|')) { $valor = explodear('|', $tipo, 1); $tipo = explodear('|', $tipo, 0); }
	switch ($tipo) { // ¿Quien tiene acceso?
		case 'internet': case 'anonimos': $t = _('todo el mundo (Internet)'); break;
		case 'ciudadanos_global': $t = _('todos los ciudadanos de VirtualPol'); break;
		case 'ciudadanos': $t = _('todos los ciudadanos de').' '.($valor==''?_('la plataforma').' '.PAIS:' '._('las plataformas').': <em>'.PAIS.' '.$valor.'</em>'); break;
		case 'excluir': $t = _('todos los ciudadanos excepto').': <em>'.$valor.'</em>'; break;
		case 'privado': if ($valor == '') { $t = _('nadie'); } else { $t = _('los ciudadanos').': '.$valor; } break;
		case 'confianza': $t = _('ciudadanos con confianza mayor o igual a').' '.confianza($valor).' (<a href="/censo/confianza">'._('Ver confianza').'</a>)'; break;
		case 'nivel': $t = _('ciudadanos con nivel').' <em>'.$valor.'</em> '._('o mayor').' (<a href="/cargos">'._('Ver cargos').'</a>)'; break;
		
		case 'examenes':
			$val = array();
			$result = sql("SELECT titulo AS nom FROM examenes WHERE pais = '".PAIS."' AND ID IN (".implode(',', explode(' ', $valor)).")");
			while($r = r($result)) { $val[] = $r['nom']; }
			$t = _('ciudadanos con los exámenes aprobados').': <a href="/examenes">'.implode(', ', $val).'</a>';
			break;

		case 'cargo':
			$val = array();
			if ($valor == '') { $valor = 'null'; }
			$result = sql("SELECT cargo_ID, nombre AS nom FROM cargos WHERE pais = '".PAIS."' AND cargo_ID IN (".implode(',', explode(' ', $valor)).") ORDER BY nivel DESC");
			while($r = r($result)) { $val[] = '<a href="/cargos/'.$r['cargo_ID'].'"><img src="'.IMG.'cargos/'.$r['cargo_ID'].'.gif" title="'.$r['nom'].'" alt="'.$r['nom'].'" width="16" height="16" /></a>'; }
			$t = _('ciudadanos con cargo').': '.implode(' ', $val).' (<a href="/cargos">'._('Ver cargos').'</a>)';
			break;

		case 'afiliado':
			$val = array();
			$result = sql("SELECT siglas AS nom FROM partidos WHERE pais = '".PAIS."' AND ID IN (".implode(',', explode(' ', $valor)).")");
			while($r = r($result)) { $val[] = $r['nom']; }
			$t = _('ciudadanos afiliados al partido').' <a href="/partidos">'.implode('', $val).'</a>';
			break;

		case 'grupos':
			$val = array();
			$result = sql("SELECT nombre AS nom FROM grupos WHERE pais = '".PAIS."' AND grupo_ID IN (".implode(',', explode(' ', $valor)).")");
			while($r = r($result)) { $val[] = $r['nom']; }
			$t = _('ciudadanos afiliados al grupo:').' <a href="/grupos">'.implode(', ', $val).'</a>';
			break;
		
		case 'monedas': $t = _('ciudadanos con al menos').' <em>'.$valor.'</em> '._('monedas'); break;
		case 'socios': $t = _('ciudadanos inscritos como socios de').' '.PAIS; break;
		case 'autentificados': $t = _('ciudadanos autentificados'); break;
		case 'supervisores_censo': $t = _('Supervisores del Censo'); break;
		case 'antiguedad': $t = _('ciudadanos con al menos').' <em>'.$valor.'</em> '._('días de antigüedad');  break;
		default: $t = _('nadie'); break;
	}
	return $t;
}

function sql_acceso($tipo, $valor='', $pais=false) {
	$valor = trim($valor);
	if ($pais == false) { $pais = PAIS; }
	if (is_array($tipo)) { $valor = $tipo[1]; $tipo = $tipo[0]; }
	elseif (stristr($tipo, '|')) { $valor = explodear('|', $tipo, 1); $tipo = explodear('|', $tipo, 0); }
	switch ($tipo) {
		case 'internet': case 'anonimos': $rt = "'true' = 'true'"; break;
		case 'ciudadanos_global': $rt = "estado = 'ciudadano'"; break;
		case 'ciudadanos': $valor .= ' '.$pais; $a = explode(' ', trim($valor)); $rt = "estado = 'ciudadano' AND pais IN ('".implode("','", $a)."')"; break;
		case 'excluir': $rt = "nick NOT IN ('".implode("','", explode(' ', trim($valor)))."')"; break;
		case 'privado': $rt = "nick IN ('".implode("','", explode(' ', trim($valor)))."')"; break;
		case 'afiliado': $rt = "pais = '".$pais."' AND partido_afiliado = '".$valor."'"; break;
		case 'confianza':  $rt = "voto_confianza >= '".$valor."'"; break;
		case 'nivel': $rt = "pais = '".$pais."' AND nivel >= '".$valor."'"; break;
		case 'cargo': foreach (explode(' ', $valor) AS $ID) { $a[] = "CONCAT(' ', cargos, ' ') LIKE '% ".$ID." %'"; } $rt = "pais = '".$pais."' AND (".implode(' OR ',$a).")"; break;
		case 'grupos': foreach (explode(' ', $valor) AS $ID) { $a[] = "CONCAT(' ', grupos, ' ') LIKE '% ".$ID." %'"; } $rt = "pais = '".$pais."' AND (".implode(' OR ',$a).")"; break;
		case 'examenes': foreach (explode(' ', $valor) AS $ID) { $a[] = "CONCAT(' ', examenes, ' ') LIKE '% ".$ID." %'"; } $rt = "pais = '".$pais."' AND (".implode(' OR ',$a).")"; break;
		case 'monedas': $rt = "pols >= '".$valor."'"; break;
		case 'socios': $rt = "pais = '".$pais."' AND socio = 'true'"; break;
		case 'autentificados': $rt = "dnie = 'true'"; break;
		case 'supervisores_censo': $rt = "estado = 'ciudadano' AND SC = 'true'"; break;
		case 'antiguedad': $rt = "estado = 'ciudadano' AND fecha_registro < '".date('Y-m-d H:i:s', (time()-($valor*86400)))."'"; break;
		default: $rt = "'true' = 'false'";
	}
	return $rt;
}



function control_acceso($titulo=false, $name='', $acceso='', $cfg='', $quitar_array='', $inline=false) {
	$html = ($titulo==false?'':'<fieldset><legend>'.$titulo.'</legend>').'<select name="'.$name.'">';
	$quitar_array = explode(' ', $quitar_array);
	$array = nucleo_acceso('print');
	foreach ($array AS $a => $b) { if (in_array($a, $quitar_array)) { unset($array[$a]); } }
	foreach ($array AS $at => $at_var) {
		$html .= '<option value="'.$at.'"'.($at==$acceso?' selected="selected"':'').' />'.ucfirst(str_replace('_', ' ', $at)).'</option>';
	}
	$html .= '</select>'.($inline?' ':'<br />').'<input type="text" name="'.$name.'_cfg" size="18" maxlength="9000" id="'.$name.'_cfg_var" value="'.$cfg.'" />'.($titulo==false?'':'</fieldset>');
	return $html;
}



function notificacion($user_ID, $texto='', $url='', $emisor='sistema') {
	global $pol;
	switch ($user_ID) {

		case 'print':
			if (isset($pol['user_ID'])) {
				$t = ''; $total_num = 0;

				// NOTIFICACION VOTACIONES
				$pol['config']['info_consultas'] = 0;
				$result = sql("SELECT v.ID, pregunta, acceso_votar, acceso_cfg_votar, acceso_ver, acceso_cfg_ver 
				FROM votacion `v`
				LEFT OUTER JOIN votacion_votos `vv` ON v.ID = vv.ref_ID AND vv.user_ID = '".$pol['user_ID']."'
				WHERE v.estado = 'ok' AND (v.pais = '".PAIS."' OR acceso_votar IN ('supervisores_censo', 'privado')) AND vv.ID IS null");
				while($r = r($result)) {
					if ((nucleo_acceso($r['acceso_votar'], $r['acceso_cfg_votar'])) AND (nucleo_acceso($r['acceso_ver'], $r['acceso_cfg_ver']))) { 
						$pol['config']['info_consultas']++;
						$nuevos_num++;
						$total_num++;
						$t .= '<li><a href="/votacion/'.$r['ID'].'" class="noti-nuevo">'._('Votación').': '.$r['pregunta'].'</a></li>';
					}
				}

				// NOTIFICACIONES
				$result = sql("SELECT noti_ID, visto, texto, url, MAX(time) AS time_max, COUNT(*) AS num FROM notificaciones WHERE user_ID = '".$pol['user_ID']."' GROUP BY visto, texto ORDER BY visto DESC, time_max DESC LIMIT 7");
				while($r = r($result)) {
					$total_num += $r['num'];
					if ($r['visto'] == 'false') { $nuevos_num += $r['num']; }
					$t .= '<li><a href="'.($r['visto']=='false'?'/?noti='.$r['noti_ID']:$r['url']).'"'.($r['visto']=='false'?' class="noti-nuevo"':'').(substr($r['url'], 0, 4)=='http'?' target="_blank"':'').'>'.$r['texto'].($r['num']>1?'<span class="md">'.$r['num'].'</span>':'').'</a></li>';
				}

			} else { $t = '<li><a href="'.REGISTRAR.'?p='.PAIS.'" class="noti-nuevo">'._('Primer paso: Crea tu ciudadano').'</a></li>'; $total_num = 1; $nuevos_num = 1; }
			return '<li id="menu-noti"'.($nuevos_num!=0?' class="menu-sel"':'').'><a href="/hacer">'._('Notificaciones').'<span class="md">'.$nuevos_num.'</span></a><ul><li style="border-bottom:1px dotted #DDD;"><a href="/elecciones">'._('Proceso diario').' '.(time()>strtotime(date('Y-m-d 20:00:00'))?_('hace'):_('en')).' <b>'.timer(date('Y-m-d 20:00:00')).'</b></a></li>'.$t.($total_num==0?'<li>'._('No hay notificaciones').'</li>':'').'<li style="text-align:right;"><a href="/hacer"><b>'._('¿Qué hacer?').'</b></a></li></ul></li>'.($nuevos!=0?'':'<script type="text/javascript">p_scroll = true;</script>');
			break;


		case 'visto': 
			$result = sql("SELECT noti_ID, visto, texto, url FROM notificaciones WHERE noti_ID = '".$texto."' LIMIT 1");
			while($r = r($result)) {
				if ($r['visto'] == 'false') {
					sql("UPDATE notificaciones SET visto = 'true' WHERE visto = 'false' AND user_ID = '".$pol['user_ID']."' AND texto = '".$r['texto']."'"); 
				}
				redirect($r['url']);
			}
			break;

		default: 
			sql("INSERT INTO notificaciones (user_ID, texto, url, emisor) VALUES ('".$user_ID."', '".$texto."', '".$url."', '".$emisor."')");
			return true;
	}
}

function escape($a, $escape=true, $html=true) {
	// SQL INYECTION
	$a = nl2br($a);
	$a = str_replace("'", '&#39;', $a);
	$a = str_replace('"', '&quot;', $a);
	$a = str_replace(array("\x00", "\x1a"), '', $a);
	if ($escape == true) { $a = mysql_real_escape_string($a); }

	// XSS
	if ($html == false) { $a = strip_tags($a); }
	$js_filter = 'video|javascript|vbscript|expression|applet|xml|blink|script|embed|object|iframe|frame|frameset|ilayer|bgsound|onabort|onactivate|onafterprint|onafterupdate|onbeforeactivate|onbeforecopy|onbeforecut|onbeforedeactivate|onbeforeeditfocus|onbeforepaste|onbeforeprint|onbeforeunload|onbeforeupdate|onblur|onbounce|oncellchange|onchange|onclick|oncontextmenu|oncontrolselect|oncopy|oncut|ondataavailable|ondatasetchanged|ondatasetcomplete|ondblclick|ondeactivate|ondrag|ondragend|ondragenter|ondragleave|ondragover|ondragstart|ondrop|onerror|onerrorupdate|onfilterchange|onfinish|onfocus|onfocusin|onfocusout|onhelp|onkeydown|onkeypress|onkeyup|onlayoutcomplete|onload|onlosecapture|onmousedown|onmouseenter|onmouseleave|onmousemove|onmouseout|onmouseover|onmouseup|onmousewheel|onmove|onmoveend|onmovestart|onpaste|onpropertychange|onreadystatechange|onreset|onresize|onresizeend|onresizestart|onrowenter|onrowexit|onrowsdelete|onrowsinserted|onscroll|onselect|onselectionchange|onselectstart|onstart|onstop|onsubmit|onunload';
	$a = preg_replace('/(<|&lt;|&#60;|&#x3C;|&nbsp;)('.$js_filter.')/', 'nojs', $a);
	$a = str_replace(array('accion.php', 'ajax.php'), 'nojs', $a);
	return $a;
}


function gbarra($porcentaje, $size=false, $mostrar_porcentaje=true) {
	return '<div class="gbarra"'.($size!=false?' style="width:'.$size.'px;"':'').'><strong class="barra" style="width:'.round($porcentaje).'%;">'.($mostrar_porcentaje==true?round($porcentaje).'%':'&nbsp;').'</strong></div>';
}

function pass_key($t, $type='sha') {
	switch ($type) {
		case 'md5': return hash('md5', $t); break;
		default: return hash('sha256', CLAVE_SHA.$t);
	}
}

function timer($t, $es_timestamp=false, $pre=null) {
	if ($pre == true) { if (time() > strtotime($t)) { $pre = _('Hace').' '; } else { $pre = _('En').' '; } }
	return $pre.'<span class="timer" value="'.($es_timestamp==true?$t:strtotime($t)).'" title="'.$t.'"></span>'; 
}

function ocultar_IP($IP, $tipo='IP') { 
	// devuelve el host o IP indicado cortando alguno de sus datos, para proteger la privacidad 
	if ($tipo == 'IP') {
		$trozos = explode('.', long2ip($IP));
		return $trozos[0].'.'.$trozos[1].'.'.$trozos[2].'.*';
	} elseif ($tipo == 'host') {
		$host = '';
		$hosts = explode('.', $IP);
		if (strlen($hosts[count($hosts)-3]) > 4) { 
			$host = $hosts[count($hosts)-4].'.'.$hosts[count($hosts)-3].'.'.$hosts[count($hosts)-2].'.'.$hosts[count($hosts)-1]; 
		}
		return '*.'.$host;
	}
}

function redirect($url, $r301=true) {
	if ($r301 == true) { header('HTTP/1.1 301 Moved Permanently'); } 
	header('Location: '.$url); 
	mysql_close();
	exit;
}

function get_supervisores_del_censo() {
	$result = sql("SELECT ID, nick FROM users WHERE SC = 'true' AND estado = 'ciudadano'");
	while($r = r($result)){ $sc[$r['ID']] = $r['nick']; }
	return $sc; // Devuelve un array con los Supervisores del Censo activos. Formato: $array[user_ID] = nick;
}

function duracion($t) {
	if ($t > 172800) { $d = round($t/86400).' '._('días'); }
	elseif ($t > 7200) { $d = round($t/3600).' '._('horas'); }
	elseif ($t > 120) { $d = round($t/60).' '._('min'); }
	else { $d = $t.' '._('seg'); }
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
					//global $vp;
					//$bg = ' style="background:'.$vp['bg'][$pais].';"';
					$add_class .= ' redondeado';
				}
				if (($estado) && ($estado != 'ciudadano')) { 
					return '<a href="/perfil/'.$a.'" class="nick'.$add_class.' '.$estado.'"'.$bg.'>'.$a.'</a>';
				} else {
					return '<a href="/perfil/'.$a.'" class="nick'.$add_class.'"'.$bg.'>'.$a.'</a>';
				}
			} else { 
				return '<span title="Expirado">&dagger;</span>'; 
			} 
			break;
		case 'partido': if ($a) { return '<a href="/partidos/'.strtolower($a).'">'.$a.'</a>'; } else { return 'Ninguno'; } break;
		case 'documento': return '<a href="/doc/'.$a.'">/doc/'.$a.'</a>'; break;
	}
}

function mumble_url($canal='') {
	global $pol;
	return 'mumble://'.$pol['nick'].'@occupytalk.org:64738/Other?version=1.2.0';
}

function accion_url($pais=false) { 
	//return SSL_URL.'source/accion.php?http_host='.($pais==false?HOST:strtolower($pais).'.'.DOMAIN).'&'; 
	return '/accion.php?';
}
function vp_url($path='/', $pais=PAIS) { return 'http://'.strtolower($pais).'.'.DOMAIN.$path; }
function error($txt='Acción no permitida o erronea') { redirect('http://'.HOST.'/?error='.base64_encode($txt)); }
function num($num, $dec=0) { return number_format(round($num, $dec), $dec, ',', '.'); }
function explodear($pat, $str, $num) { $exp = explode($pat, $str); return $exp[$num]; }
function implodear($pat, $str, $num) { $exp = implode($pat, $str); return $exp[$num]; }
function entre($num, $min, $max) { if ((is_numeric($num)) AND ($num >= $min) AND ($num <= $max)) { return true; } else { return false; } }
function direccion_IP($tipo='ip') { return ($tipo=='longip'?ip2long($_SERVER['REMOTE_ADDR']):$_SERVER['REMOTE_ADDR']); }
function avatar($user_ID, $size='') { return '<img src="'.IMG.'a/'.$user_ID.($size?'_'.$size:'').'.jpg" alt="'.$user_ID.'"'.($size!=''?' width="'.$size.'" height="'.$size.'" class="redondeado"':'').' />'; }
function pols($pols) { return '<span class="'.($pols<0?'pn':'pp').'">'.number_format($pols, 2, '.', ',').'</span>'; }
function tiempo($dias=0, $hora='H:i:s', $tipo='pasado') { 
	return date('Y-m-d '.$hora, ($tipo=='pasado'?time()-(86400*round($dias)):time()+(86400*round($dias)))); 
}

function boton($texto, $url=false, $confirm=false, $size=false, $pols='', $html_extra=false) {
	if (($pols=='') OR (ECONOMIA == false)) {
		return '<button'.($url==false?' disabled="disabled"':' onClick="'.($confirm!=false?'if(!confirm(\''.$confirm.'\')){return false;}':'').($url!='submit'?'window.location.href=\''.$url.'\';return false;':'').'"').($size!=false?' class="'.$size.'"':'').($html_extra!=false?$html_extra:'').'>'.$texto.'</button>';
	} else {
		global $pol;
		return '<span class="amarillo"><input type="submit" value="'.$texto.'"'.($pol['pols']<$pols?' disabled="disabled"':' onClick="'.($confirm!=false?'if(!confirm(\''.$confirm.'\')){return false;}':'').'window.location.href=\''.$url.'\';"').' class="large blue" />'.(ECONOMIA?' &nbsp; '.pols($pols).' '.MONEDA.'':'').($html_extra!=false?$html_extra:'').'</span>';
	}
}

// FUNCION MALA. REEMPLAZAR URGENTE.
function paginacion($type, $url, $ID, $num_ahora=null, $num_total=null, $num='10') {
	global $p_limit, $p_paginas, $p_init;
	if (!$num_total) {
		switch ($type) {
			case 'subforo': 
				$result = mysql_fetch_row(sql("SELECT COUNT(ID) FROM ".SQL."foros_hilos WHERE ID = '".$ID."'"));
				$num_total = $result[0];
				break;
			case 'eventos': 
				$result = mysql_fetch_row(sql("SELECT COUNT(ID) FROM log WHERE pais = '".PAIS."'"));
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
				$html .= '<span class="amarillo">&nbsp;<b><a href="'.$el_url.'">'.$i.'</a></b>&nbsp;</span> ';
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

function confianza($num, $votos_num=false) {
	if ($num >= 10) { $t = 'vcc">+'; }
	elseif ($num >= 0) { $t = 'vc">+'; } 
	elseif ($num > -10) { $t = 'vcn">'; }
	else { $t = 'vcnn">'; }
	return '<span'.($votos_num!=false?' title="+'.(($votos_num+$num)/2).' -'.($votos_num-(($votos_num+$num)/2)).' ('.$votos_num.' votos)"':'').' class="'.$t.$num.'</span>';
}

?>