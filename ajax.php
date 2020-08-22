<?php # POL.VirtualPol.com — Copyright (c) 2008 Javier González González <gonzo@virtualpol.com> — MIT License 


$maxsim['output'] = 'text';


if ($_GET[1] == 'noti') {
    $pol['nick'] = $_SESSION['pol']['nick'];
	$pol['user_ID'] = $_SESSION['pol']['user_ID'];
?>
<script type="text/javascript">
$('ul.menu').each(function(){ $(this).find('li').has('ul').addClass('has-menu').append('<span class="arrow">&nbsp;</span>'); });
$('ul.menu li').hover(function(){ $(this).find('ul:first').stop(true, true).show(); $(this).addClass('hover'); }, function(){ $(this).find('ul').stop(true, true).hide(); $(this).removeClass('hover'); });
</script>
<?php
	echo notificacion('print');

} else if (($_POST['a'] == 'geo') AND (nucleo_acceso('ciudadanos_global'))) {
	header('Expires: '.gmdate("D, d M Y H:i:s", time() + 3600*24).' GMT');
	if (!isset($_POST['acceso'])) { $_POST['acceso'] = 'ciudadanos'; }
	$result = sql_old("SELECT nick, x, y FROM users WHERE x IS NOT NULL AND ".sql_acceso($_POST['acceso'], $_POST['acceso_cfg'])." LIMIT 5000"); // ORDER BY voto_confianza DESC
	while ($r = r($result)) { echo $r['nick'].' '.$r['y'].' '.$r['x'].','; }
	echo substr($txt, 0, strlen($txt)-1);

} else if ($_GET[1] == 'ip') {
	echo ip2long($_SERVER['REMOTE_ADDR']);

} else if (($_POST['a'] == 'whois') AND (isset($_POST['nick']))) {

	$res = sql_old("SELECT ID, fecha_registro, partido_afiliado, fecha_last, nivel, online, nota, avatar, voto_confianza, estado, pais, cargo,
(SELECT siglas FROM partidos WHERE pais = '".PAIS."' AND ID = users.partido_afiliado LIMIT 1) AS partido,
(SELECT COUNT(ID) FROM ".SQL."foros_hilos WHERE user_ID = users.ID LIMIT 1) AS num_hilos,
(SELECT COUNT(ID) FROM ".SQL."foros_msg WHERE user_ID = users.ID LIMIT 1) AS num_msg
FROM users WHERE nick = '".str_replace('@', '', $_POST['nick'])."' LIMIT 1");
	while ($r = r($res)) { 
		if ($r['avatar'] == 'true') { $r['avatar'] = 1; } else { $r['avatar'] = 0; }
		if (!isset($r['partido'])) { $r['partido'] = '-'; }

		if ($r['estado'] == 'expulsado') {
			$res2 = sql_old("SELECT razon FROM expulsiones WHERE user_ID = '".$r['ID']."' AND estado = 'expulsado' ORDER BY expire DESC LIMIT 1");
			while ($r2 = r($res2)) { $expulsion = str_replace(':', '', $r2['razon']); }
		}

		echo $r['ID'] . ':' . duracion(time() - strtotime($r['fecha_registro'])) . ':' . duracion(time() - strtotime($r['fecha_last'])) . ':' . $r['nivel'] . ':' . $r['nota'] . ':' . duracion($r['online']) . ':' . $r['avatar'] . ':' . $r['partido'] . ':' . $r['num_hilos'] . '+' . $r['num_msg'] . ':' . $r['estado'] . ':' . $r['pais'] . ':' . $r['cargo'] . ':'.$expulsion.':'.$r['voto_confianza'].':';
	}

}
