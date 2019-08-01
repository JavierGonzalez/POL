<?php
/* The source code packaged with this file is Free Software, Copyright (C) 2008 by
** Javier González González <desarrollo AT virtualpol.com> <gonzomail AT gmail.com>
** It's licensed under the GNU GENERAL PUBLIC LICENSE v3 unless stated otherwise.
** You can get copies of the licenses here: http://www.gnu.org/licenses/gpl.html
** The source: http://www.virtualpol.com/codigo - TOS: http://www.virtualpol.com/TOS
** VirtualPol, The first Democratic Social Network - http://www.virtualpol.com
*/

include('inc-login.php');

$result = sql("SELECT valor, dato FROM config WHERE pais = '".PAIS."' AND autoload = 'no'");
while ($r = r($result)) { $pol['config'][$r['dato']] = $r['valor']; }


if (($_GET['a'] == 'configurar') AND (nucleo_acceso($vp['acceso']['control_socios']))) {
	// Configuracion del panel de socios

	$txt .= '<form action="'.accion_url().'a=socios&b=configurar" method="POST">

<fieldset><legend>Configurar</legend>

<table>
<tr>
<td align="right"><b>Estado</b></td>
<td><select name="socios_estado"><option value="true">Activado (inscripciones abiertas)</option><option value="false"'.($pol['config']['socios_estado']=='false'?' selected="selected"':'').'>Desactivado (inscripciones cerradas)</option></select></td>
</tr>

<tr>
<td align="right">Cargo asignado a socios</td>
<td><select name="socios_ID">
<option value="0">Ninguno.</option>';

	$result = sql("SELECT cargo_ID, nombre FROM cargos WHERE pais = '".PAIS."' AND asigna > 0 ORDER BY nivel DESC");
	while($r = r($result)) {
		$txt .= '<option value="'.$r['cargo_ID'].'"'.($pol['config']['socios_ID']==$r['cargo_ID']?' selected="selected"':'').'>'.$r['nombre'].'</option>';
	}


$txt .= '</select>
</tr>

<tr>
<td align="right">Cesión y responsable de datos</td>
<td><input type="text" name="socios_responsable" value="'.$pol['config']['socios_responsable'].'" size="40" maxlength="90" /></td>
</tr>


<tr>
<td align="right" valign="top">Condiciones adicionales</td>
<td><textarea name="socios_descripcion" style="width:500px;height:250px;">
'.strip_tags($pol['config']['socios_descripcion']).'
</textarea></td>
</tr>

<tr>
<td></td>
<td>'.boton('Guardar', 'submit', false, 'blue').'</td>
</tr>

</table>

</fieldset>

</form>';



} elseif ((($_GET['a'] == 'inscritos') OR ($_GET['a'] == 'asociados')) AND (nucleo_acceso('ciudadanos')) AND (nucleo_acceso($vp['acceso']['control_socios']))) {
	// Lista de inscritos

	function comprobar_nif($nif) {
		$letras = explode(',','T,R,W,A,G,M,Y,F,P,D,X,B,N,J,Z,S,Q,V,H,L,C,K,E');
		if (
		(strlen($nif)!=9) ||
		(!is_long($entero=intval(substr($nif,0,8)))) ||
		(!in_array($letra=strtoupper(substr($nif,8,1)),$letras)) ||
		($letra!=$letras[$entero%23])
		){ return false; } else { return true; }
	}


	if ($_GET['a'] == 'asociados') { $socios = true; } else { $socios = false; }
	$txt .= '<table>
<tr>
<th></th>
<th>Asociado</th>
<th>Nick</th>
<th>Nombre</th>
<th>NIF</th>
<th>Email</th>
<th></th>
<th>País</th>
<th>CP</th>
<th></th>
<th></th>
</tr>';
	$result = sql("SELECT *, (SELECT nick FROM users WHERE ID = socios.user_ID LIMIT 1) AS nick FROM socios WHERE pais = '".PAIS."'".($socios?" AND estado = 'socio'":" AND estado != 'socio'")." LIMIT 10");
	while($r = r($result)) { 
		$txt .= '<tr>
<td nowrap>'.($socios?boton('Rescindir', accion_url().'a=socios&b=rescindir&ID='.$r['ID'], '¿Estás seguro de querer EXPULSAR a este socio?', 'small red'):boton('Rechazar', accion_url().'a=socios&b=rescindir&ID='.$r['ID'], '¿Estás seguro de querer ELIMINAR esta inscripción?', 'small red').' '.boton('Aprobar', accion_url().'a=socios&b=aprobar&ID='.$r['ID'], false, 'small blue')).'</td>
<td>'.$r['pais'].$r['socio_ID'].'</td>
<td>'.crear_link($r['nick']).'</td>
<td nowrap>'.$r['nombre'].'</td>
<td'.(comprobar_nif($r['NIF'])?'':' style="color:red;"').'>'.$r['NIF'].'</td>
<td>'.($r['contacto_email']?'<span title="'.$r['contacto_email'].'">Email</span>':'').'</td>
<td>'.($r['contacto_telefono']?'<span title="'.$r['contacto_telefono'].'">T</span>':'').'</td>
<td class="gris">'.$r['pais_politico'].'</td>
<td class="gris">'.$r['cp'].'</td>
<td class="gris">'.$r['localidad'].'</td>
<td class="gris" nowrap>'.($r['direccion']?'<span title="'.$r['direccion'].'">D</span>':'').'</td>
</tr>';
	}
	$txt .= '</table>';

} elseif (true) {
	$es_socio = false;
	$result = sql("SELECT ID, estado, socio_ID FROM socios WHERE pais = '".PAIS."' AND user_ID = '".$pol['user_ID']."' LIMIT 1");
	while($r = r($result)) { $es_socio = true; $socio_estado = $r['estado']; $socio_numero = PAIS.$r['socio_ID']; }

	if (nucleo_acceso('socios')) {
		// Eres socio correctamente. Info basica. Botones para eliminar.
		
		$txt .= '<p>Eres socio. Tu numero de asociado es: <b>'.$socio_numero.'</b></p><hr /><p>'.boton('Darse de baja de socio y eliminar datos asociados', accion_url().'a=socios&b=cancelar', '¿Estás seguro de querer DARTE DE BAJA como socio?', 'red').'</p>';

	} elseif (nucleo_acceso('ciudadanos')) {
		// Formulario para ser socio

		if ($es_socio) {
			$txt .= '<p>¡Correcto! Tu inscripción de socio está en cola de aprobación.</p><hr /><p>'.boton('Cancelar inscripción y eliminar datos asociados', accion_url().'a=socios&b=cancelar', false, 'red').'</p>';

		} elseif ($pol['config']['socios_estado'] == 'true') {
			$result = sql("SELECT email, nombre FROM users WHERE ID = '".$pol['user_ID']."' LIMIT 1");
			while($r = r($result)) { $email = $r['email']; $nombre = $r['nombre']; }

			$txt .= '<form action="'.accion_url().'a=socios&b=inscribirse" method="POST">

<fieldset><legend>Inscripción de socio de '.PAIS.'</legend>

<table>
<tr>
<td align="right">Nombre completo</td>
<td><input type="text" name="nombre" value="'.(strlen($nombre)>2?$nombre:'').'" size="30" maxlength="90" required /></td>
</tr>

<tr>
<td align="right">NIF</td>
<td class="gris"><input type="text" name="NIF" value="" size="10" maxlength="10" placeholder="123456789A" pattern="[0-9]{8,9}[A-Z]{1}" required /> Ejemplo: 123456789A</td>
</tr>
</table>


<fieldset><legend>Lugar</legend>

<table>
<tr>
<td align="right">País</td>
<td><select name="pais_politico"><option value="ESP">España</option></select></td>
</tr>

<tr>
<td align="right">Localidad</td>
<td><input type="text" name="localidad" value="" size="15" maxlength="20" required /></td>
</tr>

<tr>
<td align="right">Código postal</td>
<td><input type="text" name="cp" value="" size="5" maxlength="8" pattern="[0-9]{5,6}" required /></td>
</tr>

<tr>
<td align="right">Domicilio</td>
<td><input type="text" name="direccion" value="" size="40" maxlength="90" /> (opcional)</td>
</tr>
</table>

</fieldset>


<fieldset><legend>Contacto</legend>

<table>
<tr>
<td align="right">Dirección de email</td>
<td><input type="email" name="contacto_email" value="'.$email.'" size="30" maxlength="90" required /></td>
</tr>

<tr>
<td align="right">Teléfono</td>
<td><input type="tel" name="contacto_telefono" value="" size="15" maxlength="10" pattern="[0-9+]{9,13}" /> (opcional)</td>
</tr>
</table>

</fieldset>

<blockquote>
<p>
* Serás socio tras un proceso de aprobación.<br />
'.($pol['config']['socios_responsable']?'* Los datos introducidos en este formulario serán cedidos a: <b>'.$pol['config']['socios_responsable'].'</b>.<br />':'').'
* Podrás dejar de ser socio y eliminar todos los datos asociados en cualquier momento.
</p>

<p class="rich">'.$pol['config']['socios_descripcion'].'</p>

<p><input type="checkbox" name="aprobado" value="true" required /> <b>He leído y acepto las condiciones.</b></p>

</blockquote>

<p>'.boton('Inscribirse como socio de '.$pol['config']['pais_des'], 'submit', false, 'large blue').'</p>

</fieldset>

</form>';
		} else { $txt .= '<p>Las inscripciones para socio estan cerradas temporalmente.</p>'; }
	} else { $txt .= '<p>Debes ser ciudadano de '.PAIS.' para poder inscribirte como socio.</p>'; }
} else { $txt .= '<p>Debes estar registrado para poder inscribirte como socio.</p>'; }


if (nucleo_acceso($vp['acceso']['control_socios'])) {
	$contador['socio'] = 0; $contador['inscrito'] = 0;
	$result = mysql_query("SELECT COUNT(*) AS num, estado FROM socios WHERE pais = '".PAIS."' GROUP BY estado", $link);
	while($r = mysql_fetch_array($result)){ $contador[$r['estado']] = $r['num']; }
	$txt_tab['/socios/inscritos'] = 'Pendientes ('.$contador['inscrito'].')';
	$txt_tab['/socios/asociados'] = 'Asociados ('.$contador['socio'].')';
	$txt_tab['/socios/configurar'] = 'Configurar';
}


//THEME
$txt_title = _('Socios');
$txt_nav = array('/socios'=>'Socios');
$txt_menu = 'demo';
include('theme.php');
?>