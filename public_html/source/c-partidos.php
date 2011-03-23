<?php 
include('inc-login.php');


if ($_GET['a']) {

        $result = mysql_query("SELECT 
ID, siglas, nombre, descripcion, fecha_creacion, ID_presidente, 
(SELECT nick FROM ".SQL_USERS." WHERE ID = ".SQL."partidos.ID_presidente LIMIT 1) AS nick_presidente
FROM ".SQL."partidos 
WHERE siglas = '" . trim($_GET['a']) . "'
LIMIT 1", $link);
        while($row = mysql_fetch_array($result)){

                if (($_GET['b'] == 'editar')) { //edit/  AND ($row['ID_presidente'] == $pol['user_ID'])

                        $txt_title = 'Editar Partido';
                        //print listas
                        $candidatos_num = 0;
                        $result2 = mysql_query("SELECT user_ID, orden, 
(SELECT nick FROM ".SQL_USERS." WHERE ID = ".SQL."partidos_listas.user_ID LIMIT 1) AS nick,
(SELECT cargo FROM ".SQL_USERS." WHERE ID = ".SQL."partidos_listas.user_ID LIMIT 1) AS cargo,
(SELECT voto_confianza FROM ".SQL_USERS." WHERE ID = ".SQL."partidos_listas.user_ID LIMIT 1) AS confianza
FROM ".SQL."partidos_listas
WHERE ID_partido = '" . $row['ID'] . "'
ORDER BY ID ASC", $link);
                        while($row2 = mysql_fetch_array($result2)){ 
                                if (!$li_listas) {  $li_presi = ' &larr; Candidato a Presidente'; } else { $li_presi = ''; }
                                $li_listas .= '<li><form action="/accion.php?a=partido-lista&b=del&ID=' . $row['ID'] . '" method="post"><input type="hidden" name="user_ID" value="' . $row2['user_ID'] . '"  /><input style="height:26px;" type="submit" value="X" /> <img src="'.IMG.'cargos/'.$row2['cargo'].'.gif" /><b>' . crear_link($row2['nick']) . ' ' . confianza($row2['confianza']) . '</b> ' . $li_presi . '</form></li>' . "\n"; 
                                $candidatos_num++;
                        }

                        $ciudadanos_num = 0;
                        $result2 = mysql_query("SELECT ID, nick, fecha_last, voto_confianza,
(SELECT user_ID FROM ".SQL."partidos_listas WHERE ID_partido = '" . $row['ID'] . "' AND user_ID = ".SQL_USERS.".ID LIMIT 1) AS en_lista, 
(SELECT user_ID FROM ".SQL."estudios_users WHERE ID_estudio = '6' AND user_ID = ".SQL_USERS.".ID AND estado = 'ok' LIMIT 1) AS es_diputado
FROM ".SQL_USERS." 
WHERE estado != 'validar' AND estado != 'desarrollador' AND partido_afiliado = '" . $row['ID'] . "' AND pais = '".PAIS."'
ORDER BY nick DESC", $link);
                        while($row2 = mysql_fetch_array($result2)){
                                if ((!$row2['en_lista']) AND ($row2['es_diputado'])) {
                                        $ciudadanos .= '<option value="' . $row2['ID'] . '">' . $row2['nick'] . ' (' . confianza($row2['voto_confianza']) . ', ' . duracion(time() - strtotime($row2['fecha_last'])) . ')</option>';
                                        $ciudadanos_num++;
                                }
                                $ciudadanos_full .= '<option value="' . $row2['ID'] . '">' . $row2['nick'] . ' (' . confianza($row2['voto_confianza']) . ', ' . duracion(time() - strtotime($row2['fecha_last'])) . ')</option>';
                        }

                        $text = $row['descripcion'];
                        $text = $row['ideología'];

                        if (($candidatos_num >= $pol['config']['num_escanos']) OR ($ciudadanos_num == 0)) { $disabled = ' disabled="disabled"'; } else { $disabled = ''; }

                        include('inc-functions-accion.php');
                        $txt .= '<h1><img src="'.IMG.'doc-edit.gif" alt="Editar" /> <a href="/partidos/">Partidos</a>: ' . $row['siglas'] . ' (' . $row['nombre'] . ')</h1>
<ul id="partido">

<li><form action="/accion.php?a=partido-lista&b=add&ID=' . $row['ID'] . '" method="post"><select name="user_ID">' . $ciudadanos . '</select> <input type="submit" value="A&ntilde;adir a la lista"' . $disabled . ' /> (afiliados a tu partido y con estudio de Diputado)</form><br /></li>

<li><b>Lista:</b> Candidatos a Diputados (' . $candidatos_num . ' de un m&aacute;ximo de '.$pol['config']['num_escanos'].' candidatos)
<ol>
' . $li_listas . '
</ol><br />
</li>


<li>Introducci&oacute;n, descripci&oacute;n e ideario del partido:
<form action="/accion.php?a=partido-lista&b=edit&ID=' . $row['ID'] . '" method="post">
' . editor_enriquecido('text', $text) . '
<input type="submit" value="Guardar" /><br /><br /></form></li>


<li><form action="/accion.php?a=partido-lista&b=del-afiliado&ID=' . $row['ID'] . '" method="post"><select name="user_ID">' . $ciudadanos_full . '</select> <input type="submit" value="Desafiliar" /></form><br /></li>


<li><form action="/accion.php?a=partido-lista&b=ceder-presidencia&ID=' . $row['ID'] . '" method="post"><select name="user_ID">' . $ciudadanos_full . '</select> <input type="submit" value="Ceder Presidencia" onClick="if (!confirm(\'&iquest;Estas convencido de que quieres CEDER tu cargo de Presidente de ' . $row['siglas'] . ' para siempre?\')) { return false; }" /> (Ceder&aacute;s el control total del partido a este ciudadano)</form></li>
</ul>';



                } else {

                        //count afiliados
                        $result2 = mysql_query("SELECT COUNT(ID) FROM ".SQL_USERS." WHERE partido_afiliado = '" . $row['ID'] . "' AND pais = '".PAIS."'", $link);
                        while($row2 = mysql_fetch_array($result2)){ $num_afiliados = $row2['COUNT(ID)']; }

                        //print listas
                        $num_listas = 0;
                        $result2 = mysql_query("SELECT user_ID,
(SELECT nick FROM ".SQL_USERS." WHERE ID = ".SQL."partidos_listas.user_ID LIMIT 1) AS nick,
(SELECT voto_confianza FROM ".SQL_USERS." WHERE ID = ".SQL."partidos_listas.user_ID LIMIT 1) AS confianza,
(SELECT fecha_last FROM ".SQL_USERS." WHERE ID = ".SQL."partidos_listas.user_ID LIMIT 1) AS fecha_last
FROM ".SQL."partidos_listas
WHERE ID_partido = '" . $row['ID'] . "'
ORDER BY ID ASC", $link);
                        while($row2 = mysql_fetch_array($result2)){ 
                                $li_presi = '';
                                if (!$li_listas) {  $li_presi = ' &larr; Candidato a Presidente'; }
                                if ($row['ID_presidente'] == $row2['user_ID']) {  $li_presi .= ' &larr; Presidente de ' . $row['siglas']; }
                                $li_listas .= '<li><b>' . crear_link($row2['nick']) . '</b> (' . confianza($row2['confianza']) . ', ' . duracion(time() - strtotime($row2['fecha_last'])) . ')' . $li_presi . '</li>' . "\n";
                                $num_listas++;
                        }


                                $result3 = mysql_query("SELECT nick, estado
FROM ".SQL_USERS."
WHERE partido_afiliado = '" . $row['ID'] . "' AND pais = '".PAIS."' AND estado = 'ciudadano'
ORDER BY fecha_registro ASC", $link);
                                while($row3 = mysql_fetch_array($result3)){ 
                                        $afiliados .= ' ' . crear_link($row3['nick'], 'nick', $row3['estado']) . ','; 
                                }

                        $txt_title = $row['siglas'] . ' - ' . $row['nombre'];

                        $txt .= '<h1><a href="/partidos/">Partidos</a>: ' . $row['siglas'] . ' | ' . $row['nombre'] . '</h1>

<p>' . $row['descripcion'] . '</p>

<ul id="partido">
<li>Ideología de ' . $row['siglas'] . ': <b>' . ($row['ideología']) . '</b><br /><br /></li>
<li>Presidente de ' . $row['siglas'] . ': <b>' . crear_link($row['nick_presidente']) . '</b><br /><br /></li>
<li>Afiliados: <b>' . $num_afiliados . '</b>' . $afiliados . '<br /><br /></li>
<li>Lista: <b>' . $num_listas . ' (Candidatos a Diputados)</b>
<ol>
' . $li_listas . '</ol></li>
</ul>';

                }
                $siglas_lower = strtolower($row['siglas']);
                $txt .= '<hr style="width:100%;" />';
                if (($row['ID_presidente'] == $pol['user_ID']) AND (!$_GET['b'])) { //PARA PRESIDENTE
                        $txt .= '<span><form><input type="button" value="Editar" onClick="window.location.href=\'/partidos/' . $siglas_lower . '/editar/\';" /> <a href="/partidos/"><b>Ver todos los partidos</b></a></form></span>';
                } elseif ($_GET['b']) { $txt .= '<span style="float:right;"><form><input type="button" value="Eliminar" onClick="if (!confirm(\'&iquest;Estas convencido de que quieres ELIMINAR para siempre tu partido?\')) { return false; } else { window.location.href=\'/accion.php?a=eliminar-partido&siglas=' . $row['siglas'] . '\'; }"></form></span><span><a href="/partidos/' . $siglas_lower . '/"><b>Volver a tu partido</b></a></span>';
                } else { $txt .= '<span>' . boton('Afiliarse', '/form/afiliarse/' . $siglas_lower . '/') . ' <a href="/partidos/"><b>Ver todos los partidos</b></a></span>'; }


        } if (!$txt) { /*404*/ }


        $txt_header .= '<style type="text/css">#partido li { margin-top:5px; }</style>';
} else {

        $txt .= '<h1>Partidos:</h1>
<br />
<table border="0" class="pol_table">
<tr>
<th>Siglas</th>
<th>Nombre</th>
<th>Ideología</th>
<th><acronym title="Afiliados/En Lista">Afiliados</acronym>&darr;</th>
<th>Presidente</th>
<th><acronym title="Candidato a Presidente de '.PAIS.'">Candidato</acronym></th>
<th>Antig&uuml;edad</th>
<th><acronym title="Participaci&oacute;n en Elecciones">Elec *</acronym></th>
</tr>';



        $result = mysql_query("SELECT 
ID, siglas, nombre, fecha_creacion, ID_presidente,
(SELECT nick FROM ".SQL_USERS." WHERE ID = ".SQL."partidos.ID_presidente LIMIT 1) AS nick_presidente, 
(SELECT (SELECT nick FROM ".SQL_USERS." WHERE ID = ".SQL."partidos_listas.user_ID LIMIT 1) AS nick FROM ".SQL."partidos_listas WHERE ID_partido = ".SQL."partidos.ID ORDER BY ID ASC LIMIT 1) AS nick_candidato, 
(SELECT COUNT(ID) FROM ".SQL_USERS." WHERE partido_afiliado = ".SQL."partidos.ID AND pais = '".PAIS."' LIMIT 1) AS afiliados, 
(SELECT COUNT(ID) FROM ".SQL."partidos_listas WHERE ID_partido = ".SQL."partidos.ID LIMIT 1) AS num_lista
FROM ".SQL."partidos 
WHERE estado = 'ok'
ORDER BY num_lista DESC, afiliados DESC, nombre DESC", $link);
        while($row = mysql_fetch_array($result)){

                $num_lista = $row['num_lista'];
                if ($num_lista >= 1) { $num_lista = '<b>' . $num_lista . '</b>'; $elecciones = '<b style="color:blue;">Si</b>'; } else { $elecciones = '<b style="color:red;">No</b>'; }

                if ($row['nick_candidato']) { $nick_candidato = '<b>' . crear_link($row['nick_candidato']) . '</b>'; } else { $nick_candidato = ''; }

                $txt .= '<tr><td align="right" valign="top"><b style="font-size:20px;">' . crear_link($row['siglas'], 'partido') . '</b></td><td>' . $row['nombre'] . $row['ideología'] '</td><td><b>' . $row['afiliados'] . '</b>/' . $num_lista . '</td><td>' . crear_link($row['nick_presidente']) . '</td><td>' . $nick_candidato . '</td><td align="right">' . duracion(time() - strtotime($row['fecha_creacion'])) . '</td><td>' . $elecciones . '</td></tr>' . "\n";
        }
        $txt .= '</table>';

        $txt .= '<p>' . boton('Crear Partido', '/form/crear-partido/', false, false, $pol['config']['pols_partido']) . '</p><p>* Para que un partido pueda participar en las Elecciones ha de tener al menos un candidato en su lista. Para poder a&ntilde;adir candidatos en la lista, se ha de ser el Presidente del partido, el candidato ha de estar afiliado y con el examen de Diputado aprobado.</p>';
        $txt_title = 'Partidos Pol&iacute;ticos';
}




//THEME
include('theme.php');
?>