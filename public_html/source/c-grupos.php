<?php
include('inc-login.php');
$txt_title = 'Grupos';

switch ($_GET['a']) {

case 'crear':
	$txt_title = 'Crear grupo';
	$txt_nav = array('/grupos'=>'Grupos', 'Crear grupo');
	$txt_tab = array('/grupos'=>'Ver grupos', '/grupos/crear'=>'Crear grupo');

	$txt .= '<form action="/accion.php?a=grupos&b=crear" method="POST">

<p>Nombre del grupo: <input type="text" name="nombre" size="20" maxlength="40" /> <input type="submit" value="Crear grupo"'.(nucleo_acceso($vp['acceso']['control_grupos'])?'':' disabled="disabled"').' /> (Pueden crear grupos: '.verbalizar_acceso($vp['acceso']['control_grupos']).')

</form>
';	
	break;

default:
	$txt_nav = array('/grupos'=>'Grupos');
	$txt_tab = array('/grupos/crear'=>'Crear grupo');

	$txt .= '<p>Afiliandote a grupos podr&aacute;s acceder a sus foros, documentos, chats y votaciones. Puedes afiliarte a m&uacute;ltiples grupos.</p>

<form action="/accion.php?a=grupos&b=afiliarse" method="POST">

<fieldset><legend>Grupos</legend>

<table border="0">
<tr>
<th></th>
<th></th>
<th>Afiliados</th>
<th>Foros asociados</th>
<th align="right">ID</th>
</tr>
';

	$result = mysql_query("SELECT ID, nick, grupos FROM users WHERE pais = '".PAIS."' AND estado = 'ciudadano' AND grupos != ''", $link);
	while($r = mysql_fetch_array($result)) {
		foreach (explode(' ', $r['grupos']) AS $grupo_ID) { $users_array[$grupo_ID][] = $r['nick']; }
	}

	$result = mysql_query("SELECT url, title, acceso_cfg_leer FROM ".SQL."foros WHERE estado = 'ok' AND acceso_leer = 'grupos'", $link);
	while($r = mysql_fetch_array($result)) {
		foreach (explode(' ', $r['acceso_cfg_leer']) AS $grupo_ID) { $foros_array[$grupo_ID][] = '<a href="/foro/'.$r['url'].'"><b>'.$r['title'].'</b></a>'; }
	}

	$result = mysql_query("SELECT * FROM grupos WHERE pais = '".PAIS."' ORDER BY num DESC", $link);
	while($r = mysql_fetch_array($result)) {
		$txt .= '<tr>
<td align="right"><input type="checkbox" name="grupo_'.$r['grupo_ID'].'" id="grupo_'.$r['grupo_ID'].'" value="true"'.(nucleo_acceso('grupos', $r['grupo_ID'])?' checked="checked"':'').' /></td>
<td><b><label for="grupo_'.$r['grupo_ID'].'" class="inline">'.$r['nombre'].'</label></b></td>
<td align="right" style="font-size:18px;color:#777;" title="AFILIADOS: '.(is_array($users_array[$r['grupo_ID']])?implode(' ', $users_array[$r['grupo_ID']]):'').'"><b>'.$r['num'].'</b> (<span class="punteado">Ver</span>)</td>
<td>'.(is_array($foros_array[$r['grupo_ID']])?implode(' ', $foros_array[$r['grupo_ID']]):'').'</td>
<td width="100" align="right" style="color:#888;">'.$r['grupo_ID'].'</td>
<td>'.(nucleo_acceso($vp['acceso']['control_grupos'])?boton('Eliminar', '/accion.php?a=grupos&b=eliminar&grupo_ID='.$r['grupo_ID'], false, 'red'):'').'</td>
</tr>';
	}

	$txt .= '</table></fieldset>

<p>'.boton('Guardar afiliación', 'submit', false, 'blue').'</p>
	
</form>';
}



$txt_menu = 'demo';
include('theme.php');
?>