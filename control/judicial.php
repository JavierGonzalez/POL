<?php # POL.VirtualPol.com — Copyright (c) 2008 Javier González González <gonzo@virtualpol.com> — MIT License 



$txt_title = _('Control').': '._('Judicial');
$txt_nav = array('/control'=>_('Control'), _('Judicial'));


echo '
<h2>1. '._('Sanciones').'</h2><hr />

<table border="0" cellspacing="1" cellpadding="">
<tr>
<th></th>
<th>'._('Ciudadano').'</th>
<th>'._('Hace').'</th>
<th></th>
</tr>';



$result = sql_old("SELECT *,
(SELECT nick FROM users WHERE ID = transacciones.emisor_ID LIMIT 1) AS nick
FROM transacciones
WHERE pais = '".PAIS."' AND concepto LIKE '<b>SANCION %' AND receptor_ID = '-1'
ORDER BY time DESC");
while($r = r($result)){
    echo '<tr><td>'.pols('-'.$r['pols']).' '.MONEDA.'</td><td><b>'.crear_link($r['nick']).'</b></td><td><acronym title="'.$r['time'].'">'.timer($r['time']).'</acronym></td><td>'.$r['concepto'].'</td></tr>' . "\n";
}




if (!nucleo_acceso($vp['acceso']['control_sancion'])) { $disabled = ' disabled="disabled"'; }

echo '</table><br />

<form action="/accion/sancion" method="post">

<ol>
<li><b>'._('Nick').':</b>.<br /><input type="text" value="" name="nick" size="20" maxlength="20" /><br /><br /></li>

<li><b>'.MONEDA.' de multa:</b> el importe de la sanción, maximo 5000 '.MONEDA.' (en caso de no tener la cantidad requerida, se quedará en negativo).<br /><input style="color:blue;text-align:right;" type="text" name="pols" size="4" value="1" maxlength="4" /> '.MONEDA.'<br /><br /></li>

<li><b>Concepto:</b> breve frase con la razón de la sanción.<br /><input type="text" name="concepto" size="50" maxlength="100" /><br /><br /></li>

<li><input type="submit" style="color:red;" value="'._('Efectuar sanción').'"' . $disabled . ' /> &nbsp; <span style="color:red;"><b>[acción irreversible]</b></span></li></ol></form>
        
';