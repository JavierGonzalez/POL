<?php # POL.VirtualPol.com — Copyright (c) 2008 Javier González González <gonzo@virtualpol.com> — MIT License 




$txt_title = _('Control');
$txt_nav = array('/control'=>_('Control'));

echo '
<p class="amarillo" style="color:red;">'._('Zonas de control cuyo acceso está reservado a los ciudadanos que ejercen estos cargos').'.</p>

<table border="0" cellspacing="6">

<tr><td nowrap="nowrap"><a class="abig" href="/control/gobierno"><b>'._('Gobierno').'</b></a></td>
<td align="right" nowrap="nowrap"></td>
<td></td></tr>

<tr>
<td nowrap="nowrap"><img src="'.IMG.'varios/kick.gif" alt="Kick" border="0" /> <a class="abig" href="/control/kick"><b>Kicks</b></a></td>
<td align="right" nowrap="nowrap"></td>
<td>Control de bloqueo temporal del acceso.</td>
</tr>

<tr>
<td nowrap="nowrap"><img src="'.IMG.'varios/expulsar.gif" alt="Expulsado" border="0" /> <a class="abig" href="/control/expulsiones"><b>'._('Expulsiones').'</b></a></td>
<td align="right" nowrap="nowrap"><img src="'.IMG.'cargos/21.gif" title="Supervisor del Censo" /></td>
<td>Expulsiones permanentes por incumplimiento del <a href="/TOS">TOS</a>.</td>
</tr>

<tr>';


if (isset($sc[$pol['user_ID']])) {
	echo '<td nowrap="nowrap"><a class="abig" href="/control/supervisor-censo"><b>'._('Supervisión del censo').'</b></a></td>';
} else {
	echo '<td nowrap="nowrap"><b class="abig gris">'._('Supervisión del censo').'</b></td>';
}

foreach ($sc AS $user_ID => $nick) {
	$supervisores .= crear_link($nick).' '; 
}

echo '
<td align="right" nowrap="nowrap"><img src="'.IMG.'cargos/21.gif" title="Supervisor del Censo" /></td>
<td>Información sobre el censo y control de clones.<br />
Supervisores del Censo: <b>'.$supervisores.'</b><br />(los '.count($sc).' ciudadanos con más votos de confianza)</td></tr>';

if (ECONOMIA) {

	echo '
<tr><td nowrap="nowrap"><a class="abig" href="/control/judicial"><b>Judicial</b></a></td>
<td align="right" nowrap="nowrap"><img src="'.IMG.'cargos/9.gif" title="Judicial" /></td>
<td>El panel judicial que permite efectuar sanciones.</td></tr>


<tr><td nowrap="nowrap"><a class="abig" href="/mapa/propiedades"><b>Propiedades del Estado</b></a></td>
<td align="right" nowrap="nowrap"><img src="'.IMG.'cargos/40.gif" title="Arquitecto" /></td>
<td>El Arquitecto tiene el control de las propiedades del Estado.</td></tr>';

}

echo '</table>';
