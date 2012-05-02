<?php
/* The source code packaged with this file is Free Software, Copyright (C) 2008 by
** Javier González González <desarrollo AT virtualpol.com> <gonzomail AT gmail.com>
** It's licensed under the GNU GENERAL PUBLIC LICENSE v3 unless stated otherwise.
** You can get copies of the licenses here: http://www.gnu.org/licenses/gpl.html
** The source: http://www.virtualpol.com/codigo - TOS: http://www.virtualpol.com/TOS
** VirtualPol, The first Democratic Social Network - http://www.virtualpol.com
*/

include('inc-login.php');





if (($_GET['a'] == 'accion') AND ($pol['user_ID'] == 1)) {
	// ACCIONES

	if (($_GET['b'] == 'add') AND (entre(strlen($_POST['pais']), 2, 10)) AND (is_numeric($_POST['participacion'])) AND ($_POST['condiciones_extra']== 'true')) {

		mysql_query("INSERT INTO plataformas (estado, pais, asamblea, economia, user_ID, time, descripcion, participacion) 
VALUES ('pendiente', '".str_replace(' ', '', strip_tags($_POST['pais']))."', '".$_POST['asamblea']."', '".$_POST['economia']."', '".$pol['user_ID']."', '".$date."', '".strip_tags($_POST['descripcion'])."', '".$_POST['participacion']."')", $link);
		$txt .= '<p>Solicitud enviada correctamente.</p>';
	} else { redirect('http://'.HOST.'/?error='.base64_encode('Solicitud erronea.')); }


} elseif (($_GET['a'] == 'admin') AND ($pol['user_ID'] == 1)) {
	// ADMIN


} else { // FORMULARIO AÑADIR PLATAFORMA



	$txt .= '

<p>VirtualPol es la primera red social democrática. Dentro de VirtualPol coexisten plataformas diferentes, independientes y soberanas. Este formulario es para solicitar la creación de una nueva.</p>

<form action="/crear-plataforma.php?a=accion&b=add" method="post">

<fieldset><legend>Solicitar nueva plataforma en VirtualPol</legend>

'.(isset($pol['user_ID'])?'':'<p style="color:red;">'.boton('Crear ciudadano', REGISTRAR, false, 'small blue').' Debes ser ciudadano para poder solicitar una nueva plataforma.</p>').'

<table>

<tr><td colspan="3">&nbsp;</td></tr>

<tr>
<td colspan="3"><input type="checkbox" name="condiciones_extra" value="true" /> <b>He leído y acepto las siguientes condiciones adicionales</b>:<br />
<ul>
<li>No se aprobarán nuevas plataformas que supongan una duplicación de otras ya existentes, excepto que sea necesario.</li>
<li>Cada plataforma es gratuita pero consume recursos, por lo tanto debe existir una justificación para que sea creada, más allá del interés personal de una o pocas personas.</li>
<li>Cualquier plataforma podrá ser eliminada por inactividad si tiene menos de 30 usuarios activos.</li>
<li>Las plataformas serán ordenadas y priorizadas en función de su numero de ciudadanos inscritos.</li>
<li>Las siguientes opciones de configuración no podrán ser modificadas en el futuro sin aprobación de VirtualPol.</li>
<li><b>Cada plataforma es soberana</b> (es un principio de VirtualPol, ver principios en el <a href="/tos" target="_blank">TOS</a>, segundo apartado) y por lo tanto decide su propia gestión. Sin embargo la primera "legislatura" ostentará el poder el usuario que solicita la plataforma. Después el poder dependerá de unas elecciones automáticas y que -de ningún modo- se podrán detener u obstaculizar. Esto significa -explicitamente- que <b>el fundador inicial de la plataforma puede perder totalmente su control</b>, mediante principios democráticos.</li>
<li>Si -del modo que fuera- en una plataforma se rompe el principio "Democracia", cosa que tecnicamente es imposible, tendrá que ser intervenida por VirtualPol para restaurar de nuevo la democracia automática, de la forma menos intrusiva posible.</li>
</ul>
</td>
</tr>

<tr>
<td align="right"><b>Nombre</b></td>
<td><input type="text" name="pais" value="" size="10" maxlength="10" /></td>
<td>Nombre corto de la plataforma. Por ejemplo "15M" o "Hispania". Solo letras y numeros, sin espacios.</td>
</tr>

<tr><td colspan="3">&nbsp;</td></tr>

<tr>
<td align="right" valign="top"><b>Esquema de Poder</b></td>
<td colspan="2">
<input type="radio" name="asamblea" value="false" checked="checked" /> <b>Presidencial: Un presidente electo.</b><br />
Organización muy estable y operativa.<br />
<br />
<input type="radio" name="asamblea" value="true" /> <b>Parlamentario: Nueve coordinadores electos (iguales entre sí).</b><br />
Organización menos estable y operativa, pero más representativo.<br />
<br />
<em>* El sistema permite establecer jerarquias completas de cargos y responsabilidades. Un organigrama completo y escalable. Incluso elecciones independientes para cada cargo. Sin embargo debe existir un cargo "primario" y electo, del que parte toda la responsabilidad. En cualquier caso siempre estará disponible una votación de tipo "ejecutiva" que -con el apoyo de la mayoría- el sistema puede destituir y reemplazar cualquier cargo.</em>
</td>
</tr>

<tr><td colspan="3">&nbsp;</td></tr>

<tr>
<td align="right" nowrap="nowrap"><b>¿Simulador de economía?</b></td>
<td>
<select name="economia">
<option value="false" selected="selected">Desactivado</option>
<option value="true">Activado</option>
</select>
</td>
<td></td>
</tr>

<tr><td colspan="3">&nbsp;</td></tr>

<tr>
<td align="right" nowrap="nowrap"><b>Previsión de participación</b></td>
<td><input type="text" name="participacion" value="50" style="text-align:right;" size="5" maxlength="5" /></td>
<td><b>Número de ciudadanos activos previstos tras 30 días</b>: Es importante que haya un potencial considerable. Una plataforma con solo 25 usuarios carece de sentido y no suele funcionar al tener poca competencia para los cargos relevantes.</td>
</tr>

<tr><td colspan="3">&nbsp;</td></tr>


<tr>
<td align="right" valign="top"><b>Justificación</b></td>
<td colspan="2"><p>Razones y argumentos de porqué debe crearse esta plataforma en VirtualPol. Brevemente.</p>
<textarea name="descripcion" style="width:500px;height:200px;"></textarea><br />
<em>* La aprobación o rechazo dependerá directamente de este paso.</em></td>
</tr>


<tr><td colspan="3">&nbsp;</td></tr>


<tr>
<td colspan="3">'.boton('Solicitar nueva plataforma', (isset($pol['user_ID'])?'submit':false), false, 'large orange').'</td>
</tr>


</table>



</fieldset>

</form>';





}


$txt_nav = array('/crear-plataforma.php'=>'Solicitar plataforma');
$txt_tab = array('/'=>'Ver plataformas existentes');
include('theme.php');
?>