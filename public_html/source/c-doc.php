<?php 
include('inc-login.php');

if ($_GET['a']) {

	$result = mysql_query("SELECT 
ID, user_ID, url, title, text, time, nivel, time_last, cat_ID
FROM ".SQL."docs
WHERE url = '" . trim($_GET['a']) . "'
LIMIT 1", $link);
	while($row = mysql_fetch_array($result)){

		if (($_GET['b'] == 'editar') AND ($pol['nivel'] >= $row['nivel'])) { //EDITAR!

			$text = $row['text'];

			$confirm_salir = ' onClick="if (!confirm(\'&iquest;Seguro que quieres salir? No se guardar&aacute;n los cambios.\')) { return false; }"';
			include('inc-functions-accion.php');
			$txt .= '<form action="/accion.php?a=editar-documento&ID=' . $row['ID'] . '" method="post">
<input type="hidden" name="url" value="' . $row['url'] . '"  />

<h1><img src="'.IMG.'doc-edit.gif" alt="Editar Documento" /> <a href="/doc/">Documento</a>: <input type="text" name="titulo" value="' . $row['title'] . '" size="60" /></h1>


<p>Nivel de acceso: <b>' . $row['nivel'] . '</b><br />
' . form_select_nivel($row['nivel']) . ' (nivel minimo requerido para editar este documento)</p>

<p>Categor&iacute;a:<br />
' . form_select_cat('docs', $row['cat_ID']) . '</p>

<p>' . editor_enriquecido('text', $text) . '</p>

<p><input type="submit" value="Guardar" /></form></p>';

		} else { 

			$txt .= '<h1><img src="'.IMG.'doc.gif" alt="Documento" /> <a href="/doc/">Documento</a>: ' . $row['title'] . '</h1><div style="text-align:justify;margin:20px;">' . $row['text'] . '</div><br /><br /><hr style="width:100%;" />'; 

			if ($pol['nivel'] >= $row['nivel']) { 
				if (($pol['nivel'] >= 50) OR ($pol['user_ID'] == $row['user_ID'])) {
					$txt .= '<span style="float:right;"><form><input type="button" value="Eliminar" onClick="if (!confirm(\'&iquest;Estas convencido de que quieres ELIMINAR para siempre este Documento?\')) { return false; } else { window.location.href=\'/accion.php?a=eliminar-documento&url=' . $row['url'] . '\'; }"></form></span>';
				}
				$txt .= '<span><form><input type="button" value="Editar" onclick="window.location.href=\'/doc/' . $row['url'] . '/editar/\'"> Creado el <em>' . explodear(' ',$row['time'], 0) . '</em>, &uacute;ltima edici&oacute;n hace <em>' . duracion(time() - strtotime($row['time_last'])) . '</em>.</form></span>';
			} else { $txt .= '<span style="float:right;">Creado el <em>' . explodear(' ',$row['time'], 0) . '</em>, &uacute;ltima edici&oacute;n hace <em>' . duracion(time() - strtotime($row['time_last'])) . '</em>.<br />'.$row['title'].'</span><span><a href="/doc/"><b>Ver documentos</b></a></span>';  }
		}

		$txt_title = $row['title'];
	}


} else { //docs/

/*
	$txt_header .= '
<script type="text/javascript">
$(document).ready(function(){
	$("#docs dd").hide();
	$("#docs dt a").click(function(){
		$("dd:visible").slideUp("normal");
		$(this).parent().next().slideDown("normal");
		return false;
	});
});
</script>
';
*/

	$txt_title = 'Documentos';
	$txt .= '<h1><img src="'.IMG.'doc.gif" alt="Documento" /> Documentos:</h1>

<p>Los Documentos de '.PAIS.' es el sistema principal para organizar textos. Esto permite crear un documento normal u oficial para cualquier fin. Los documentos pueden confeccionarse de forma colaborativa y con diversos niveles de acceso.</p>

<p>' . boton('Crear Documento', '/form/crear-documento/') . '</p>

<div id="docs">';



	$result = mysql_query("SELECT ID, nombre, tipo FROM ".SQL."cat WHERE tipo = 'docs' ORDER BY time ASC", $link);
	while($row = mysql_fetch_array($result)){

		// CAT
		$txt .= '<div class="amarillo"><b style="font-size:20px;padding:15px;color:green;">' . $row['nombre'] . '</b></div>';
		
		$txt .= '<table border="0" cellspacing="2" cellpadding="0" class="pol_table" width="100%">';
		
		$result2 = mysql_query("SELECT 
title, url, time, nivel, estado, time_last, 
(SELECT nick FROM ".SQL_USERS." WHERE ID = ".SQL."docs.user_ID LIMIT 1) AS nick_autor
FROM ".SQL."docs
WHERE estado = 'ok' AND cat_ID = '" . $row['ID'] . "'
ORDER BY nivel DESC, title ASC", $link);
		while($row2 = mysql_fetch_array($result2)){
			if ($pol['nivel'] >= $row2['nivel']) { 
				$editar = '<form><input type="button" value="Editar" onclick="window.location.href=\'/doc/' . $row2['url'] . '/editar/\'" style="margin-bottom:-16px;"></form>';
			}

			$txt .= '<tr><td align="right"><b>' . $row2['nivel'] . '</b></td><td><b><a href="/doc/' . $row2['url'] . '/">' . $row2['title'] . '</a></b></td><td>' . crear_link($row2['nick_autor']) . '</td><td align="right">' . str_replace(' ', '&nbsp;', duracion(time() - strtotime($row2['time_last']))) . '</td><td>' . explodear(' ', $row2['time'], 0) . '</td><td>' . $editar . '</td></tr>' . "\n";

			$editar = '';
		}
		$txt .= '</table><br />';
	}


	$txt .= '</div>

<p>' . boton('Crear Documento', '/form/crear-documento/') . '</p>';

}



//THEME

include('theme.php');
?>
