<?php
/* The source code packaged with this file is Free Software, Copyright (C) 2008 by
** Javier González González <desarrollo AT virtualpol.com> <gonzomail AT gmail.com>
** It's licensed under the GNU GENERAL PUBLIC LICENSE v3 unless stated otherwise.
** You can get copies of the licenses here: http://www.gnu.org/licenses/gpl.html
** The source: http://www.virtualpol.com/codigo - TOS: http://www.virtualpol.com/TOS
** VirtualPol, The first Democratic Social Network - http://www.virtualpol.com
*/

include('config.php');
include('source/inc-functions.php');
include('source/inc-functions-accion.php');


///////////////////////////////////////////////////////
exit; // REVISAR TODO EL CODIGO ANTES DE ABRIR ESTA API



if ($_GET['pass']) {
	$link = conectar();
}

//Funciones
function api_pass() { return substr(md5(mt_rand(1000000000,9999999999)), 0, 12); }


// COMANDO API
if (($_GET['a']) AND ($_GET['pass'])) {
	header('connection: close');
	header('Content-Type: text/plain');

	$txt = 'error: pass';
	//check PASS
	$res = mysql_query("SELECT * FROM  users WHERE api_pass = '".$_GET['pass']."' LIMIT 1", $link);
	while($r = mysql_fetch_array($res)){
		mysql_query("UPDATE users SET api_num = api_num + 1 WHERE ID = '".$r['ID']."' LIMIT 1", $link);
		
		$txt = 'ok';
		
		switch ($_GET['a']) { //acciones


			case 'info': 
				$txt = "nick=".$r['nick']."&user_ID=".$r['ID']."&estado=".$r['estado']."&pais=".$r['pais']."&pols=".$r['pols']."&fecha_registro=".$r['fecha_registro']."&partido_afiliado=".$r['partido_afiliado']."&nota_media=".$r['nota']."&cargo=".$r['cargo']."&confianza=".$r['voto_confianza']."&nivel=".$r['nivel'];
				break;


			case 'transacciones':
				$txt = '';
				if (substr($_GET['cuenta'], 0, 1) == '-') {
					$result2 = mysql_query("SELECT *,
(SELECT nick FROM users WHERE transacciones.emisor_ID != '".$_GET['cuenta']."' AND ID = transacciones.emisor_ID LIMIT 1) AS emisor_nick,
(SELECT nick FROM users WHERE transacciones.receptor_ID != '".$_GET['cuenta']."' AND ID = transacciones.receptor_ID LIMIT 1) AS receptor_nick
FROM transacciones 
WHERE pais = '".PAIS."' AND emisor_ID = '".$_GET['cuenta']."' OR receptor_ID = '".$_GET['cuenta']."' 
ORDER BY time DESC
LIMIT 500", $link);
					while($r2 = mysql_fetch_array($result2)){ 
						if ($r2['emisor_ID'] == $_GET['cuenta']) { 
							$r2['pols'] = '-'.$r2['pols']; 
						}
						if (substr($r2['emisor_ID'], 0, 1) != '-') { $r2['emisor_ID'] = $r2['emisor_nick']; }
						if (substr($r2['receptor_ID'], 0, 1) != '-') { $r2['receptor_ID'] = $r2['receptor_nick']; }

						$txt .= $r2['ID'].'|'.$r2['pols'].'|'.$r2['emisor_ID'].'|'.$r2['receptor_ID'].'|'.strtotime($r2['time']).'|'.$r2['concepto']."|\n";
					}
				}
				if (!$txt) { $txt = 'error'; }
				break;



			case 'transferencia': exit;
				$txt = 'error';
				if ((ctype_digit($_GET['pols'])) AND ($_GET['pols'] > 0) AND ($_GET['destino']) AND ($_GET['concepto'])) {
					$concepto = strip_tags(trim($_GET['concepto']));
					$origen = false;
					$destino = false;

					// CHECK: ORIGEN
					if ($_GET['origen']) {
						// cuenta
						$_GET['origen'] = str_replace('-', '', $_GET['origen']);
						$result = mysql_query("SELECT ID FROM cuentas WHERE pais = '".PAIS."' AND ID = '".$_GET['origen']."' AND pols >= '".$_GET['pols']."' AND (user_ID = '".$r['ID']."' OR '".$r['nivel']."' >= nivel) LIMIT 1", $link);
						while($row = mysql_fetch_array($result)){ $origen = '-'.$_GET['origen']; }
					} else {
						// personal
						if ($r['pols'] >= $_GET['pols']) { $origen = $r['ID']; }
					}

					
					// CHECK: DESTINO
					if (substr($_GET['destino'], 0, 1) == '-') {
						// Cuenta
						$_GET['destino'] = str_replace('-', '', $_GET['destino']);
						$result = mysql_query("SELECT ID FROM cuentas WHERE pais = '".PAIS."' AND ID = '".$_GET['destino']."' LIMIT 1", $link);
						while($row = mysql_fetch_array($result)){ $destino = '-'.$row['ID']; }
					} else {
						// NICK
						$result = mysql_query("SELECT ID FROM  users WHERE nick = '".$_GET['destino']."' LIMIT 1", $link);
						while($row = mysql_fetch_array($result)){ $destino = $row['ID']; }
					}

					// ejecuta transferencia
					if (($origen) AND ($destino)) { 
						pols_transferir($_GET['pols'], $origen, $destino, 'API: '.$concepto); 
						$txt = 'ok'; 
					}
				}

				break;
		} 



	}

	echo $txt;
	
} else {

?>
<html>
<head>
<title>API VirtualPol</title>

<style type="text/css">
li em { color:green; font-size:18px; }
</style>

</head>
<body>

<h1>API &nbsp; VirtualPol</h1>

<p>La utilidad de esta API es facilitar a los no-humanos la ejecuci&oacute;n de acciones en VirtualPol. Esto permitir&aacute; crear aplicaciones externas que hagan automatismos o procesos en lote.</p>

<blockquote>

<h2>1. Nomenclatura:</h2>

<p>Las consultas a la API se hacen mediante par&aacute;metros en una URL, metodo GET. Hay dos par&aacute;metros esenciales, par&aacute;metro "pass" (la clave personal API) y el parametro "a" (acci&oacute;n).</p>

<p>Ejemplo: <em>http://www.virtualpol.com/api.php?pass=55d4bf2edf8b&a=debug</em></p>

<h2>2. Clave API:</h2>

<p>La clave API es un c&oacute;digo de 12 caracteres alfanum&eacute;ricos. Por ejemplo: <em>55d4bf2edf8b</em>. Este c&oacute;digo debe mantenerse en secreto, ya que equivale a la contrase&ntilde;a. Esta clave permite el acceso restringido a la API, identificando a un Ciudadano. Por lo tanto una clave API efect&uacute;a acciones equivalentes a las de un Ciudadano normal.</p> 

<p>En caso de verse comprometida la clave o una simple sospecha de esto, se debe generar una nueva contrase&ntilde;a que anular&iacute;a la antigua. Este control est&aacute; en el perfil y puede hacerse tantas veces se quiera.</p>

<p>En caso de introducirse una clave incorrecta, devolver&aacute;: "pass error".</p>

<h2>3. Consultas:</h2>

<p>La API permite DOS tipos de consultas API: <b>acciones</b> y <b>obtener informaci&oacute;n</b>.</p>

<p>Nota: los par&aacute;metros a continuaci&oacute;n deben ir precedidos.<br />
<b style="color:green;">http://www.virtualpol.com/api.php?pass=CLAVE_API</b></p>

<blockquote>

<h3>3.1. Acciones:</h3>
<ul>
<li><s><em>&a=<b>transferencia</b></em></s> [EN DESARROLLO]<br />
<em>&pols=POLS</em> &nbsp; (n&uacute;mero)<br />
<em>&origen=cuenta_ID</em> &nbsp; (opcional, solo necesario en caso de que el origen sea una cuenta bancaria)<br />
<em>&destino=NICK|cuenta_ID</em> &nbsp; (nick o ID en negativo de una cuenta en concreto, ejemplo: -1)<br />
<em>&concepto=TEXTO</em><br />
Devuelve: ok|error<br />
Transfiere una cantidad de POLs desde tu Ciudadano o una cuenta de tu propiedad, a otro Ciudadano o cuenta cual quiera. Verifica si los datos son correctos y si hay fondos suficientes. En caso de petici&oacute;n erronea no efectua ninguna acci&oacute;n.<br />
</li>
</ul>

<h3>3.2. Obtener informaci&oacute;n:</h3>
<ul>

<li><em>&a=<b>debug</b></em><br />
Permite hacer un test.<br /><br /></li>

<li><em>&a=<b>info</b></em><br />
Devuelve informacion completa del usuario.<br /><br /></li>

<li><em>&a=<b>transacciones</b></em><br />
<em>&cuenta=cuenta_ID</em> (ID de cuenta bancaria, con signo negativo. Ejemplo: -1)<br />
Formato: <b>transaccion_ID|MONEDA|emisor|receptor|fecha(timestamp)|el concepto|</b><br />
Devuelve la lista de las ultimas 500 transferencias de MONEDA de una cuenta bancaria.<br /><br /></li>

</ul>

</blockquote>

</blockquote>

<hr />

<p><a href="http://www.virtualpol.com/"><b>http://www.virtualpol.com/</b></a></p>
</body></html>

<?php
}

if ($link) { mysql_close($link); }
?>
