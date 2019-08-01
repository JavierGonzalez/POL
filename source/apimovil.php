<?php
/* The source code packaged with this file is Free Software, Copyright (C) 2008 by
** Javier González González <desarrollo AT virtualpol.com> <gonzomail AT gmail.com>
** It's licensed under the GNU GENERAL PUBLIC LICENSE v3 unless stated otherwise.
** You can get copies of the licenses here: http://www.gnu.org/licenses/gpl.html
** The source: http://www.virtualpol.com/codigo - TOS: http://www.virtualpol.com/TOS
** VirtualPol, The first Democratic Social Network - http://www.virtualpol.com
*/
include('inc-login.php');

if (isset($pol['nick']))
{
	//Informacion basica del usuario	
	//var_dump($pol);
	$datosUsuario = array (
		'nick'		=> $pol["nick"], 
		'pais' 		=> $pol["pais"],
	);
	
	// Informacion del avatar
	$result = sql("SELECT cargo, pols, avatar, nombre, sc
	FROM users 
	WHERE nick = '".$pol['nick']."'
	LIMIT 1");
	while($r = r($result)){
		/* Composicion de la imagen en c-perfil.php
		// <img src="'.IMG.'a/'.$r['ID'].'.jpg" alt="'.$nick.'" title="Avatar" width="120" height="120" style="border:1px solid #AAA;" />
		// Constante de imagenes en config http://www.'.DOMAIN.'/img/
		*/
		if ($r['avatar']){
			$avatar = array ('avatar' => strtolower($r['avatar']),'avatar_url'=>'http://www.virtualpol.com/img/a/'.$pol['user_ID'].'.jpg');
		}else{
			$avatar = array ('avatar' => strtolower($r['avatar']));
		}
		$datosUsuario['nombre'] = $r['nombre'];
                $datosUsuario['pols'] = $r['pols'];
		$datosUsuario['cargo'] = $r['cargo'];
		$datosUsuario['sc'] = $r['sc'];
	//var_dump($r);
	}
	
	// Notificaciones
	$nuevos_num = 0;
	$votaci_num= 0;
	
	$votaciones = array ();
	//VOTACIONES
	$result = sql("SELECT v.ID, pregunta, acceso_votar, acceso_cfg_votar, acceso_ver, acceso_cfg_ver 
	FROM votacion `v`
	LEFT OUTER JOIN votacion_votos `vv` ON v.ID = vv.ref_ID AND vv.user_ID = '".$pol['user_ID']."'
	WHERE v.estado = 'ok' AND (v.pais = '".$pol["pais"]."' OR acceso_votar IN ('supervisores_censo', 'privado')) AND vv.ID IS null");
	while($r = r($result)) {
		if ((nucleo_acceso($r['acceso_votar'], $r['acceso_cfg_votar'])) AND (nucleo_acceso($r['acceso_ver'], $r['acceso_cfg_ver']))) {
			$votacion = array(
			'texto' => $r['texto'],
			'url'	=> "http://".strtolower($pol['pais']).".virtualpol.com/votacion/".$r['v.ID']
			);			
			array_push($votaciones, $votacion);
			$nuevos_num++;
			$votaci_num++;
			//echo '<li><a href="/votacion/'.$r['ID'].'" class="noti-nuevo">'._('Votación').': '.$r['pregunta'].'</a></li>';
		}
	}


	$notificaciones = array ();
	// NOTIFICACIONES
	$result = sql("SELECT noti_ID, visto, texto, url, MAX(time) AS time_max, COUNT(*) AS num FROM notificaciones 
	WHERE user_ID = '".$pol['user_ID']."' GROUP BY visto, texto ORDER BY visto DESC, time_max DESC LIMIT 7");
	while($r = r($result)) {
		//$total_num ++;
		if ($r['visto'] == 'false') { 
			$notificacion = array(
			'texto' => $r['texto'],
			'url'	=> "http://".strtolower($pol['pais']).".virtualpol.com/?noti=".$r['noti_ID']
			);
			array_push($notificaciones, $notificacion);
			$nuevos_num++; 
		}
		//$t .= 
		//'<li><a href="'.($r['visto']=='false'?'/?noti='.$r['noti_ID']:$r['url']).'"'.($r['visto']=='false'?' class="noti-nuevo"':'').(substr($r['url'], 0, 4)=='http'?' target="_blank"':'').'>'.
		//$r['texto'].($r['num']>1?'<span class="md">'.$r['num'].'</span>':'').'</a></li>';
	}
	
	$avisos = array (	
		'nuevas_notif' 	=> $nuevos_num,
		'votaciones' 	=> $votaci_num
	);

	//SELECT noti_ID, visto, texto, url, MAX(time) AS time_max, COUNT(*) AS num FROM notificaciones 
	//WHERE user_ID = '201137'  AND visto='false' GROUP BY visto, texto ORDER BY visto DESC, time_max DESC LIMIT 7

        // Plaza
	$result = sql("SELECT chat_ID, titulo,
	(SELECT COUNT(DISTINCT nick) FROM chats_msg WHERE chat_ID = chats.chat_ID AND user_ID = 0 AND tipo != 'e') AS online
	FROM chats WHERE pais = '".$pol["pais"]."' and url = '".$pol["pais"]."' ORDER BY estado ASC, online DESC, fecha_creacion ASC");

	while ($r = r($result)){
		$plaza = array (	
		'id' 	=> $r['chat_ID'],
		'titulo'=> $r['titulo']
		);
	}

	// Chats
//60*30 (ultima media hora)
	//60*60 (ultima hora)
	//60*60*24 (ultimo dia)
	$chats = array ();
	$result = sql("SELECT chat_ID, titulo, url, pais, 
	(SELECT COUNT(DISTINCT nick) FROM chats_msg WHERE chat_ID = chats.chat_ID AND user_ID = 0 AND tipo != 'e' AND time > '".date('Y-m-d H:i:s', time() - 60*60*25)."') AS online
	FROM chats HAVING online>0 ORDER BY estado ASC, online DESC, fecha_creacion ASC LIMIT 5");
	while ($r = r($result)){
if (strtolower($r['pais']) == strtolower($r['url'])){
$plataforma = true;
}
else{
$plataforma = false;
}
		if ($r['chat_ID'] != $plaza['id'])
		{
			$canal = array(
			'id' 	 => $r['chat_ID'], 
			'titulo' => trim($r['titulo']),
			'url'	 => $r['url'],
                        'plataforma'  => $plataforma
			);
			array_push($chats, $canal);
		}		
	}


	echo json_encode(
	array (
		'usuario' 	=> $datosUsuario,
		'avatarInfo'	=> $avatar,
		'avisos' 	=> $avisos,
		'votaciones'	=> $votaciones,
		'notificaciones'=> $notificaciones,	
		'plaza'		=> $plaza,
		'chatsActivos'	=> $chats
	));


}
else{
	die();
}

?>