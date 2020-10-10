<?php # POL.VirtualPol.com — Copyright (c) 2008 Javier González González <gonzo@virtualpol.com> — MIT License 



$txt_title = _('Control').': '._('Judicial');
$txt_nav = array('/control'=>_('Control'), _('Judicial'));


echo '
<h2>1. '._('Sanciones').'</h2><hr />

<table border="0" cellspacing="1" cellpadding="">
<tr>
<th></th>
<th>'._('Ciudadano / Cuenta').'</th>
<th>'._('Hace').'</th>
<th></th>
</tr>';



$result = sql_old("SELECT *,
COALESCE ((SELECT nick FROM users WHERE ID = transacciones.emisor_ID LIMIT 1),
          (SELECT nombre FROM  cuentas WHERE cuentas.ID = SUBSTRING(transacciones.emisor_ID,2) )) AS nick
FROM transacciones
WHERE pais = '".PAIS."' AND concepto LIKE 'SANCION %' AND receptor_ID = '-1'
ORDER BY time DESC");
while($r = r($result)){
    $afectado = crear_link($r['nick']);
    if ($r['emisor_ID'] < 0){
        $afectado = crear_link(substr($r['emisor_ID'],1), 'cuenta', '', '', $r['nick']);
    }

    echo '<tr><td>'.pols('-'.$r['pols']).' '.MONEDA.'</td><td><b>'
    .$afectado.
    '</b></td><td><acronym title="'.$r['time'].'">'.timer($r['time']).'</acronym></td><td>'.$r['concepto'].'</td></tr>' . "\n";
}

echo '</table><br />';

if (nucleo_acceso($vp['acceso']['control_sancion'])){
    $result = mysql_query_old("SELECT ID, nombre, pols
    FROM cuentas WHERE pais = '".PAIS."'  
    ORDER BY ID DESC, nombre ASC", $link);
    while($row = mysqli_fetch_array($result)){
        $select_origen .= '<option value="' . $row['ID'] . '"' . $extra . '>' . pols($row['pols']) . ' - ' . $row['nombre'] . '</option>' . "\n";
    }

    echo '

    <form action="/accion/sancion" method="post">

    <ol>
    <li>Seleccione el origen de la multa:<ul>
    <li><b><input id="radio_ciudadano" selected="selected" type="radio" name="origen" value="ciudadano"' . $select1_ok . ' required/>Ciudadano: <input type="text" value="" name="nick" size="20" maxlength="20" /></li>
    <li><input id="radio_cuenta" type="radio" name="origen" value="cuenta" required/>Cuenta: <select name="cuenta">
    ' . $select_origen . '
    </select></li></ul>
    <li><b>'.MONEDA.' de multa:</b> el importe de la sanción, maximo 5000 '.MONEDA.' (en caso de no tener la cantidad requerida, se quedará en negativo).<br /><input style="color:blue;text-align:right;" type="text" name="pols" size="4" value="1" maxlength="4" /> '.MONEDA.'<br /><br /></li>

    <li><b>Concepto:</b> breve frase con la razón de la sanción.<br /><input type="text" name="concepto" size="50" maxlength="100" /><br /><br /></li>

    <li><input type="submit" style="color:red;" value="'._('Efectuar sanción').'"' . $disabled . ' /> &nbsp; <span style="color:red;"><b>[acción irreversible]</b></span></li></ol></form>
            
    ';
}