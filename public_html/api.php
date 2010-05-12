<?php
/* si teneis ideas/sabéis desarrollar, estoy en ello, hablad conmigo (soy fran */

include('config.php');
include('source/inc-functions.php');
include('source/inc-functions-accion.php');

if ($_GET['pass']) {
	$link = conectar();
}

ob_start('ob_gzhandler');


//Funciones
function api_pass() { return substr(md5(mt_rand(1000000000,9999999999)), 0, 12); }


// COMANDO API
if (($_GET['a']) AND ($_GET['pass'])) {
	header('Content-Type: text/plain');
	$txt = 'pass error';
	//check PASS
	$res = mysql_query("SELECT ID AS user_ID, nick, pols, nivel, pais, fecha_registro, partido_afiliado, nota, cargo, voto_confianza FROM  ".SQL_USERS." WHERE api_pass = '" . filtro_sql($_GET['pass']) . "' LIMIT 1", $link);
	while($r = mysql_fetch_array($res)){
		mysql_query("UPDATE  ".SQL_USERS." SET api_num = api_num + 1 WHERE ID = '" . $r['user_ID'] . "' LIMIT 1", $link);
		$txt = 'ok';

		//acciones
		switch ($_GET['a']) {
			case 'info': 
			$txt = "debug: nick|user_ID|MONEDA\n" . $r['nick'] . "|" . $r['user_ID'] . "|" . $r['pols'] . "\n"; 
			$txt = "<b>Información</b>
			<br><b>Nick</b> ".$r['nick']."
			<br><b>Pais</b> ".$r['pais']."
			<br><b>Pols (en el usuario)</b> ".$r['pols']."
			<br><b>Fecha de registro</b> ".$r['fecha_registro']."
			<br><b>Partido</b> ".$r['partido_afiliado']."
			<br><b>ID del usuario</b> ".$r['user_ID']."
			<br><b>Nota</b> ".$r['nota']."
			<br><b>Cargo</b> ".$r['cargo']."
			<br><b>Confianza</b> ".$r['voto_confianza']."
			<br><b>Nivel</b> ".$r['nivel'].;
			break;


			case 'transacciones': exit; 
				$txt = '';
				if (substr($_GET['cuenta'], 0, 1) == '-') {
					$result = mysql_query("SELECT *,
(SELECT nick FROM  ".SQL_USERS." WHERE ".SQL."transacciones.emisor_ID != '" . filtro_sql($_GET['cuenta']) . "' AND ID = ".SQL."transacciones.emisor_ID LIMIT 1) AS emisor_nick,
(SELECT nick FROM  ".SQL_USERS." WHERE ".SQL."transacciones.receptor_ID != '" . filtro_sql($_GET['cuenta']) . "' AND ID = ".SQL."transacciones.receptor_ID LIMIT 1) AS receptor_nick
FROM ".SQL."transacciones 
WHERE emisor_ID = '" . filtro_sql($_GET['cuenta']) . "' OR receptor_ID = '" . filtro_sql($_GET['cuenta']) . "' 
ORDER BY time DESC
LIMIT 500", $link);
					while($row = mysql_fetch_array($result)){ 
						if ($row['emisor_ID'] == $_GET['cuenta']) { 
							$row['pols'] = '-' . $row['pols']; 
						}
						if (substr($row['emisor_ID'], 0, 1) != '-') { $row['emisor_ID'] = $row['emisor_nick']; }
						if (substr($row['receptor_ID'], 0, 1) != '-') { $row['receptor_ID'] = $row['receptor_nick']; }

						$txt .= $row['ID'] . '|' . $row['pols'] . '|' . $row['emisor_ID'] . '|' . $row['receptor_ID'] . '|' . strtotime($row['time']) . '|' . $row['concepto'] . "|\n";
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
						$result = mysql_query("SELECT ID FROM ".SQL."cuentas WHERE ID = '" . filtro_sql($_GET['origen']) . "' AND pols >= '" . filtro_sql($_GET['pols']) . "' AND (user_ID = '" . $r['user_ID'] . "' OR '" . $r['nivel'] . "' >= nivel) LIMIT 1", $link);
						while($row = mysql_fetch_array($result)){ $origen = '-' . $_GET['origen']; }
					} else {
						// personal
						if ($r['pols'] >= $_GET['pols']) { $origen = $r['user_ID']; }
					}

					
					// CHECK: DESTINO
					if (substr($_GET['destino'], 0, 1) == '-') {
						// Cuenta
						$_GET['destino'] = str_replace('-', '', $_GET['destino']);
						$result = mysql_query("SELECT ID FROM ".SQL."cuentas WHERE ID = '" . filtro_sql($_GET['destino']) . "' LIMIT 1", $link);
						while($row = mysql_fetch_array($result)){ $destino = '-' . $row['ID']; }
					} else {
						// NICK
						$result = mysql_query("SELECT ID FROM  ".SQL_USERS." WHERE nick = '" . filtro_sql($_GET['destino']) . "' LIMIT 1", $link);
						while($row = mysql_fetch_array($result)){ $destino = $row['ID']; }
					}

					// ejecuta transferencia
					if (($origen) AND ($destino)) { 
						pols_transferir($_GET['pols'], $origen, $destino, 'API: ' . $concepto); 
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
<!--<li><em>&a=<b>transferencia</b></em><br />
<em>&pols=POLS</em> &nbsp; (n&uacute;mero)<br />
<em>&origen=cuenta_ID</em> &nbsp; (opcional, solo necesario en caso de que el origen sea una cuenta bancaria)<br />
<em>&destino=NICK|cuenta_ID</em> &nbsp; (nick o ID en negativo de una cuenta en concreto, ejemplo: -1)<br />
<em>&concepto=TEXTO</em><br />
Devuelve: ok|error<br />
Transfiere una cantidad de POLs desde tu Ciudadano o una cuenta de tu propiedad, a otro Ciudadano o cuenta cual quiera. Verifica si los datos son correctos y si hay fondos suficientes. En caso de petici&oacute;n erronea no efectua ninguna acci&oacute;n.<br />
</li>-->
</ul>

<h3>3.2. Obtener informaci&oacute;n:</h3>
<ul>

<li><em>&a=<b>debug</b></em><br />
Permite hacer un test. Devuelve informaci&oacute;n sobre tu propio usuario.<br /><br /></li>

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



ob_end_flush();
if ($link) { mysql_close($link); }
?>