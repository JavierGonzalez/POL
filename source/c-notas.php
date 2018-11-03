<?php
/* The source code packaged with this file is Free Software, Copyright (C) 2008 by
** Javier González González <desarrollo AT virtualpol.com> <gonzomail AT gmail.com>
** It's licensed under the GNU GENERAL PUBLIC LICENSE v3 unless stated otherwise.
** You can get copies of the licenses here: http://www.gnu.org/licenses/gpl.html
** The source: http://www.virtualpol.com/codigo - TOS: http://www.virtualpol.com/TOS
** VirtualPol, The first Democratic Social Network - http://www.virtualpol.com
*/

include('inc-login.php');


if ($_GET['a'] == 'mmm') {

} else {						// NOTAS HOME
	$txt_title = _('Notas');
	$txt_nav = array('/notas'=>_('Notas'));

	$notame_max = 160;			// Restaurados los 160 caracteres

	$result = mysql_query("SELECT COUNT(ID) AS num FROM ".SQL."foros_msg WHERE hilo_ID = '-1'", $link);
	while($row = mysql_fetch_array($result)) { $notas_num = $row['num']; }

	$txt .= '<h1>'._('Notas').' '.$notas_num.' (<a href="/notas">'._('Actualizar').'</a>)</h1>

<br />


<table border="0" cellpadding="0" width="700" cellspacing="5" class="pol_table">';

if ($pol['estado'] == 'ciudadano') { //eliminada limitacion a extranjeros
	$txt .= '
<form action="/accion.php?a=foro&b=reply" method="post">
<input type="hidden" name="subforo" value="-1"  />
<input type="hidden" name="hilo" value="-1"  />
<input type="hidden" name="return_url" value="notas/"  />
<input type="hidden" name="encalidad" value="0"  />

<tr>
<td valign="top" align="right"><input id="notas_boton" value="'._('Enviar').'" disabled="disabled" type="submit" style="padding:5px;" /><br /><span id="notas_limit" style="font-weight:bold;font-size:24px;"><span style="color:blue;">' . $notame_max . '</span></span></td>
<td class="amarillo" colspan="2">
<input type="text" id="notas_msg" name="text" autocomplete="off" style="color:green;font-weight:bold;padding:15px 0 15px 0;width:700px;" />
</td>
</tr>
</form>
';
}

	$result = mysql_query("SELECT ID, user_ID, time, text, 
(SELECT nick FROM users WHERE ID = ".SQL."foros_msg.user_ID LIMIT 1) AS nick,
(SELECT avatar FROM users WHERE ID = ".SQL."foros_msg.user_ID LIMIT 1) AS avatar
FROM ".SQL."foros_msg
WHERE hilo_ID = '-1'
ORDER BY time DESC
LIMIT 50", $link);
        $num_id=0;
	while($row = mysql_fetch_array($result)){

		if ($row['user_ID'] == $pol['user_ID']) { 
			$boton = boton('X', '/accion.php?a=foro&b=eliminarreply&ID=' . $row['ID'] . '&hilo_ID=-1', '&iquest;Est&aacute;s seguro de querer ELIMINAR esta NOTA?') . '</span>'; 
		} else { $boton = ''; }
		
		if ($row['avatar'] == 'true') { $avatar = '<span class="navatar">' . avatar($row['user_ID'], 40) . '</span>'; } else { $avatar = ''; }

		$txt .= '<tr onmouseover="show(\'div'.$num_id.'\')" onmouseout="hide(\'div'.$num_id.'\')"><td align="right"><b class="big">' . crear_link($row['nick']) . '</b><br /><acronym title="' . $row['time'] . '">' . duracion(time() - strtotime($row['time'])) . '</acronym></td><td valign="top" class="amarillo">' . $avatar . $row['text'] . '</td><td width="1">' . $boton . '</td>
                    <td><div id="div'.$num_id.'"style="display: inline; visibility: hidden"><a href="http://twitter.com/share" class="twitter-share-button" data-url="http://'.strtolower(PAIS).'.'.DOMAIN.'/notas/" data-text="'.$row['text'].' #'.DOMAIN.'" data-count="none" data-lang="es">
    Tweet</a></div></td></tr>' . "\n";
                 $num_id++;
	}

	$txt .= '</table>';
}

$txt_header .= '<style type="text/css">
h1 a { color:#4BB000; }
#enviar { background:#FFFFB7; padding:20px 0 20px 50px; }
.navatar { float:left; margin:-4px 10px -4px 0; }
</style>


<script language="javascript">

function limitChars(textid, limit, infodiv) {
	var text = $("#"+textid).val(); 
	var textlength = text.length;
	if(textlength >= limit) {
		$("#" + infodiv).html("<span style=\"color:red;\">0</span>");
		$("#" + textid).val(text.substr(0,limit));
		return false;
	} else {
		$("#" + infodiv).html("<span style=\"color:blue;\">"+ (limit - textlength) +"</span>");
		return true;
	}
}
function show(id) {
    document.getElementById(id).style.visibility = "visible";
  }
  function hide(id) {
    document.getElementById(id).style.visibility = "hidden";
  }

window.onload = function(){
	setTimeout(function(){ $("#notas_boton").removeAttr("disabled"); }, 5000);
	$("#notas_msg").focus();
	$("#notas_msg").keyup(function(){
		limitChars("notas_msg", ' . $notame_max . ', "notas_limit");
	})
}

</script>

<script src="http://platform.twitter.com/widgets.js" type="text/javascript"></script>
';


//THEME
$txt_title = _('Notas');
$txt_menu = 'comu';
include('theme.php');
?>