<?php


// ### NUCLEO ACCESO 3.0
function nucleo_acceso($tipo, $valor='') {
	global $_SESSION;
	$rt = false;
	if (is_array($tipo)) { $valor = $tipo[1]; $tipo = $tipo[0]; }
	switch ($tipo) {
		case 'internet': case 'anonimos': if ($_SESSION['pol']['estado'] != 'expulsado') { $rt = true; } break;
		case 'ciudadanos_global': if ((isset($_SESSION['pol']['user_ID'])) AND ($_SESSION['pol']['estado'] == 'ciudadano')) { $rt = true; } break;
		case 'ciudadanos': if (($_SESSION['pol']['estado'] == 'ciudadano') && (($_SESSION['pol']['pais'] == PAIS) || (in_array($_SESSION['pol']['pais'], explode(' ', $valor))))) { $rt = true; } break;
		case 'excluir': if ((isset($_SESSION['pol']['nick'])) AND (!in_array(strtolower($_SESSION['pol']['nick']), explode(' ', strtolower($valor))))) { $rt = true; } break;
		case 'privado': if ((isset($_SESSION['pol']['nick'])) AND (in_array(strtolower($_SESSION['pol']['nick']), explode(' ', strtolower($valor))))) { $rt = true; } break;
		case 'afiliado': if (($_SESSION['pol']['pais'] == PAIS) AND ($_SESSION['pol']['partido_afiliado'] == $valor)) { $rt = true; } break;
		case 'confianza': if ($_SESSION['pol']['confianza'] >= $valor) { $rt = true; } break;
		case 'nivel': if (($_SESSION['pol']['pais'] == PAIS) AND ($_SESSION['pol']['nivel'] >= $valor)) { $rt = true; } break;
		case 'cargo': if (($_SESSION['pol']['pais'] == PAIS) AND (in_array($_SESSION['pol']['cargo'], explode(' ', $valor)))) { $rt = true; } break;
		case 'grupos': if (($_SESSION['pol']['pais'] == PAIS) AND (count(array_intersect(explode(' ', $_SESSION['pol']['grupos']), explode(' ', $valor))) > 0)) { $rt = true; } break;
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
		case 'internet': case 'anonimos': $t = 'toda Internet'; break;
		case 'ciudadanos_global': $t = 'todos los ciudadanos de VirtualPol'; break;
		case 'ciudadanos': $t = ($valor==''?'todos los ciudadanos de esta plataforma':'todos los ciudadanos de esta plataforma y '.$valor); break;
		case 'excluir': $t = 'todos los ciudadanos excepto: '.$valor; break;
		case 'privado': $t = 'los ciudadanos: '.$valor; break;
		case 'afiliado': $t = 'ciudadanos afiliados al <span title="ID: '.$valor.'">partido</span> (<a href="/partidos/">Ver partidos</a>)'; break;
		case 'confianza': $t = 'ciudadanos con confianza mayor o igual a '.confianza($valor).' (<a href="/censo/confianza/">Ver confianza</a>)'; break;
		case 'nivel': $t = 'ciudadanos con nivel mayor o igual a '.$valor.' (<a href="/cargos/">Ver cargos</a>)'; break;
		case 'cargo': $t = 'ciudadanos con cargo: '.$valor.' (<a href="/cargos/">Ver cargos</a>)'; break;
		case 'grupos': $t = 'ciudadanos afiliados al grupo: '.$valor.' (<a href="/grupos/">Ver grupos</a>)'; break;
		case 'monedas': $t = 'ciudadanos con al menos '.$valor.' monedas'; break;
		case 'autentificados': $t = 'ciudadanos autentificados'; break;
		case 'supervisores_censo': $t = 'Supervisores del Censo'; break;
		case 'antiguedad': $t = 'ciudadanos con al menos '.$valor.' dias de antig&uuml;edad';  break;
	}
	return $t;
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

function r301($url) {
	global $link;
	header('HTTP/1.1 301 Moved Permanently'); 
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
	elseif ($t > 129600) { $d = round($t / 86400) . ' d&iacute;as'; }
	elseif ($t > 86400) { $d = '1 d&iacute;a'; }
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
					return '<a href="/perfil/'.$a.'/" class="nick'.$add_class.' '.$estado.'"'.$bg.'>'.$a.'</a>';
				} else {
					return '<a href="/perfil/'.$a.'/" class="nick'.$add_class.'"'.$bg.'>'.$a.'</a>';
				}
			} else { 
				return '<span title="Usuario expirado">&dagger;</span>'; 
			} 
			break;
		case 'partido': if ($a) { return '<a href="/partidos/' . strtolower($a) . '/">' . $a . '</a>'; } else { return 'Ninguno'; } break;
		case 'documento': return '<a href="/doc/' . $a . '/">/doc/' . $a . '/</a>'; break;
	}
}

function num($num, $dec=0) { return number_format(round($num, $dec), $dec, ',', '.'); }

function explodear($pat, $str, $num) { $exp = explode($pat, $str); return $exp[$num]; }
function implodear($pat, $str, $num) { $exp = implode($pat, $str); return $exp[$num]; }

function boton($value, $url='', $confirm=false, $m='', $pols='') {
	if ($pols != '') {
		global $pol;
		if ($pol['pols'] >= $pols) { $disabled = ''; } else { $disabled = ' disabled="disabled"'; }
		if ($url) {
			return '<span class="amarillo"><input type="button" value="' . $value . '"' . $disabled . ' onClick="window.location.href=\'' . $url . '\';" /> &nbsp; ' . pols($pols) . ' ' . MONEDA . '</span>';
		} else {
			return '<span class="amarillo"><input type="submit" value="' . $value . '"' . $disabled . ' /> &nbsp; ' . pols($pols) . ' ' . MONEDA . '</span>';
		}
	} elseif (($confirm) AND ($confirm != 'm')) { 
		return '<input type="button" value="' . $value . '" onClick="if (!confirm(\'' . $confirm . '\')) { return false; } else { window.location.href=\'' . $url . '\'; }" />';
	} elseif (!$url) {
		return '<input type="button" value="' . $value . '" disabled="disabled" />';
	} else {
		if ($confirm == 'm') { $style = ' style="margin-bottom:-16px;"'; }
		return '<input type="button" value="' . $value . '" onClick="window.location.href=\'' . $url . '\';"' . $style . ' />'; 
	}
}

function form_select_nivel($nivel_select='') {
	global $pol, $link;
	$f .= '<select name="nivel"><option value="1">&nbsp;1 &nbsp; Ciudadano</option>';
	if ($pol['nivel'] > 1) {
		$result = mysql_query("
SELECT nombre, nivel 
FROM ".SQL."estudios 
WHERE asigna != '-1' AND nivel <= '" . $pol['nivel'] . "' 
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
FROM ".SQL."cat
WHERE tipo = '" . $tipo . "'
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
	$result = mysql_query("SELECT ID_estudio FROM ".SQL."estudios_users WHERE cargo = '1' AND user_ID = '" . $pol['user_ID'] . "'", $link);
	while($row = mysql_fetch_array($result)) { $cargos[$row['ID_estudio']] = true; }
	return $cargos;
}

function paginacion($type, $url, $ID, $num_ahora=null, $num_total=null, $num='10') {
	global $link, $p_limit, $p_paginas, $p_init;
	if (!$num_total) {
		switch ($type) {
			case 'subforo': 
				$result = mysql_fetch_row(mysql_query("SELECT COUNT(ID) FROM ".SQL."foros_hilos WHERE ID = '" . $ID . "'", $link));
				$num_total = $result[0];
				break;
			case 'eventos': 
				$result = mysql_fetch_row(mysql_query("SELECT COUNT(ID) FROM ".SQL."log", $link));
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

function confianza($num, $size=false) {
	if ($size != false) { $s = ' style="font-size:'.$size.'px;"'; } else { $s = ''; }
	if ($num >= 10) { return '<span class="vcc"'.$s.'>+'.$num.'</span>'; }
	elseif ($num >= 0) { return '<span class="vc"'.$s.'>+'.$num.'</span>'; } 
	elseif ($num > -10) { return '<span class="vcn"'.$s.'>'.$num.'</span>'; }
	else { return '<span class="vcnn"'.$s.'>'.$num.'</span>'; }
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
