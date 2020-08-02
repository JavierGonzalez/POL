<?php # POL.VirtualPol.com — Copyright (c) 2008 Javier González González <gonzo@virtualpol.com> — MIT License 


/*
pol_foros			(`ID` `url` `title` `descripcion` `acceso` `time` `estado`)
pol_foros_hilos		(`ID` `sub_ID``url` `user_ID` `title` `time` `time_last` `text` `cargo` `num`)
pol_foros_msg		(`ID``hilo_ID` `user_ID` `time` `text` `cargo`)
*/


if ($_GET[1]) {			//foro/subforo/hilo-prueba
	include('hilo.php');

} elseif ($_GET[0]) {	//foro/subforo/
	include('subforo.php');

} else {				//foro/

	$foro_oculto_num = 0;
	$result = mysql_query_old("SELECT *,
(SELECT COUNT(*) FROM ".SQL."foros_hilos WHERE sub_ID = ".SQL."foros.ID LIMIT 1) AS num
FROM ".SQL."foros
WHERE estado = 'ok'
ORDER BY time ASC", $link);
	while($r = mysqli_fetch_array($result)) {
		if (nucleo_acceso($r['acceso_leer'], $r['acceso_cfg_leer'])) {

			$txt_table .= '<tr class="amarillo">

<td colspan="3"><h2><a href="/foro/'.$r['url'].'" style="font-size:22px;margin-left:8px;"><b>'.$r['title'].'</b></a></h2></td>


<td><span style="float:right;">'.$el_acceso.'</span><span style="font-size:18px;color:green;">'.$r['descripcion'].'</span></td>





<td align="right" width="10%">'.boton('Crear Hilo', (nucleo_acceso($r['acceso_escribir'], $r['acceso_cfg_escribir'])?'/foro/'.$r['url'].'#enviar':false), false, 'large').'</td>
</tr>';

			$result2 = mysql_query_old("SELECT ID, url, user_ID, title, time, time_last, cargo, num, votos, votos_num,
(SELECT nick FROM users WHERE ID = ".SQL."foros_hilos.user_ID LIMIT 1) AS nick,
(SELECT estado FROM users WHERE ID = ".SQL."foros_hilos.user_ID LIMIT 1) AS user_estado
FROM ".SQL."foros_hilos
WHERE sub_ID = '".$r['ID']."' AND estado = 'ok'
ORDER BY time_last DESC
LIMIT ".mysqli_real_escape_string($link,$r['limite']), $link);
			while($r2 = mysqli_fetch_array($result2)) {
				if ($r2['user_estado'] != 'expulsado') {
					$time_hilo = strtotime($r2['time']);
					$txt_table .= '<tr>
	<td align="right" style="padding-right:4px;">'.crear_link($r2['nick']).'</td>
	<td align="right"><b>'.$r2['num'].'</b></td>
	<td align="right" style="padding-right:4px;">'.confianza($r2['votos'], $r2['votos_num']).'</td>
	
	<td><a'.($time_hilo>(time()-432000)?' style="font-weight:bold;"':'').' href="/foro/'.$r['url'].'/'.$r2['url'].'" class="rich">'.$r2['title'].'</a>'.($time_hilo>(time()-86400)?' <sup style="font-size:9px;color:red;">¡Nuevo!</sup>':'').'</td>
	
	<td align="right" nowrap="nowrap"><span class="timer" value="'.$time_hilo.'"></span></td>
	</tr>';
				}
			}
			$txt_table .= '<tr><td colspan="4">&nbsp;</td></tr>';
		} else { $foro_oculto_num++; }
	}



	$txt_title = 'Foro';
	$txt_nav = array('/foro'=>'Foro');
	$txt_tab = array('/grupos/'=>'Foros de grupos ('.$foro_oculto_num.')', '/foro/ultima-actividad/'=>'Última actividad', '/control/gobierno/foro/'=>'Configuración foro', );

	echo '<br />
<table border="0" cellpadding="1" cellspacing="0">

'.$txt_table.'

<tr class="amarillo">
<td width="120"><h2><a href="/foro/papelera/" style="font-size:22px;margin-left:8px;">Papelera</a></h2></td>
<td align="right"><b style="font-size:19px;"></b></td>
<td style="color:green;" colspan="2">Cuarentena de mensajes, eliminados tras 10 días.</td>
<td align="right" width="10%"></td>
</tr>
</table>';

}
