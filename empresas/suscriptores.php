<?php # POL.VirtualPol.com — Copyright (c) 2008 Javier González González <gonzo@virtualpol.com> — MIT License 

$txt_tab['/empresas/suscriptores/'.$_GET[1]] = _('Suscriptores');
$txt_tab['/empresas/articulos/'.$_GET[1]] = _('Artículos');
error_log("_GET[1]: ".$_GET[1]);
error_log("_GET[2]: ".$_GET[2]);

$result = mysql_query_old("SELECT ID, url, nombre, user_ID, descripcion, web, cat_ID, time,
(SELECT nombre FROM cat WHERE pais = '".PAIS."' AND ID = empresas.cat_ID LIMIT 1) AS cat_nom,
(SELECT url FROM cat WHERE pais = '".PAIS."' AND ID = empresas.cat_ID LIMIT 1) AS cat_url,
(SELECT nick FROM users WHERE ID = empresas.user_ID LIMIT 1) AS nick
FROM empresas
WHERE pais = '".PAIS."' AND ID = '".$_GET[1]."' 
AND user_ID = '".$pol['user_ID']."'
LIMIT 1", $link);
if($r = mysqli_fetch_array($result)) {
    
    $txt_title = _('Empresa').': ' . $r['nombre'] . ' - '._('Sector').': ' . $r['cat_nom'];
    $txt_nav = array('/empresas'=>_('Empresas'), '/empresas/'.$r['url']=>$r['cat_nom'], $r['nombre'], _('Suscriptores'));
}

$result = mysql_query_old("SELECT precio_suscripcion, periodicidad_suscripcion
FROM empresas
WHERE pais = '".PAIS."' AND ID = '".$_GET[1]."' 
AND user_ID = '".$pol['user_ID']."'
LIMIT 1", $link);

if ($r = mysqli_fetch_array($result)) {
    echo ''
        .'<form action="/accion/empresa/precio-suscripcion?ID='.$_GET[1].'" method="post">'
        .'<p>Precio de suscripcion <input type="text" name="precio_suscripcion" size="8" maxlength="20" value="'.$r['precio_suscripcion'].'" />'
        .'<p>Periodicidad 
            <select name="periodicidad_suscripcion" id="periodicidad_suscripcion">
                <option value="D" '. ($r['periodicidad_suscripcion'] == "D" ? "selected" : "").'>Diaria</option>
                <option value="S" '. ($r['periodicidad_suscripcion'] == "S" ? "selected" : "").'>Semanal</option>
                <option value="U" '. ($r['periodicidad_suscripcion'] == "U" ? "selected" : "").'>Pago único</option>
            </select>'
        .boton(_('Actualizar'), 'submit', false, 'small')
        .'</form>'
        .'<form action="/accion/empresa/anadir-suscriptor?ID='.$_GET[1].'" method="post">'
        .'<p>Nick <input type="text" name="nick" size="20" maxlength="20" value="" />'
        .boton(_('Añadir suscriptor'), 'submit', false, 'small')
        .'</form>'

        .'<table border="0" cellspacing="3" cellpadding="0" width="80%" class="pol_table">
        <tr>
            <th>Ciudadano</th>
            <th>Precio</th>
            <th>Periodicidad</th>
            <th>Fecha alta</th>
            <th>Eliminar</th>
        </tr>
        ';

    $consulta_suscriptores = mysql_query_old("SELECT nick, es.id_usuario as user_ID, precio_suscripcion, periodicidad_suscripcion, fecha_alta
    FROM empresas_suscriptores es, users u
    WHERE es.ID_EMPRESA = '".$_GET[1]."' 
    AND es.id_usuario = u.id
    LIMIT 1", $link);
    $periodicidad = "Pago único";
    if ($suscriptor['periodicidad_suscripcion'] == "D"){
        $periodicidad = "Diaria";
    }elseif ($suscriptor['periodicidad_suscripcion'] == "S"){
        $periodicidad = "Semanal";
    }
    while ($suscriptor = mysqli_fetch_array($consulta_suscriptores)) {
        echo '<tr style="text-align: center">
                <td style="text-align: left; padding-left: 20px">'.$suscriptor['nick'].'</td>
                <td>'.$suscriptor['precio_suscripcion'].' <img src="'.IMG.'varios/m.gif"></td>
                <td>'.$suscriptor['periodicidad_suscripcion'].'</td>
                <td>'.$suscriptor['fecha_alta'].'</td>
                <td><form action="/accion/empresa/eliminar-suscripcion?ID='.$_GET[1].'" method="post">
                        <input type="hidden" name="user_ID" value="'.$suscriptor['user_ID'].'">
                        <p>'.boton('Cancelar suscripción', 'submit', false, 'red').'</p>
                    </form>
                </td>
            </tr>';
    }
    echo '</table>';
}

