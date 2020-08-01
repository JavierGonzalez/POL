<?php # POL.VirtualPol.com — Copyright (c) 2008 Javier González González <gonzo@virtualpol.com> — MIT License 



/*
pol_mercado	(ID, user_ID, title, descripcion, pols ,tipo, time, estado) 
pol_pujas		(ID, mercado_ID, user_ID, pols, time)
*/

 if (($_GET[1] == 'frase') AND ($_GET[2] == 'editar') AND ($pol['config']['pols_fraseedit'] == $pol['user_ID'])) {

	$url = str_replace("http://", "", explodear("\"", $pol['config']['pols_frase'], 1));
	$frase = explodear("<", explodear(">", $pol['config']['pols_frase'], 1), 0);

	echo '<h1>Mercado: ' . $_GET[1] . ' (editar)</h1>
<form action="/accion/mercado/editarfrase" method="post">
<p>Frase: <input value="' . $frase . '" type="text" name="frase" size="60" maxlength="80" /><br />
http://<input type="text" name="url" size="63" maxlength="80" value="' . $url . '" /><br />
<input type="submit" value="Editar" /></b></p>
</form>
<p><a href="/mercado/frase/">Ver Subasta frase</a></p>';

} elseif ($_GET[1] == 'frase') {

	$queda = duracion(strtotime(date('Y-m-d 20:00:00')) - time());
	if (substr($queda, 0, 1) == '-') { $queda = '1 dia'; }

	$result = mysql_query_old("SELECT valor,
(SELECT nick FROM users WHERE ID = config.valor LIMIT 1) AS nick
FROM config WHERE pais = '".PAIS."' AND dato = 'pols_fraseedit' LIMIT 1", $link);
	while($row = mysqli_fetch_array($result)){ $nick = $row['nick']; }

	echo '<h1>Subasta ' . $_GET[1] . '</h1>

<p>La frase pertenece actualmente a: <b>' . crear_link($nick) . '</b></p>

<p>Finaliza en <b>' . $queda . '</b>. Cada dia a las 20:00 se termina la Subasta y comienza una nueva. En ese momento el pujador m&aacute;s alto podr&aacute; editar la frase y su enlace al que desee, durante las siguientes 24h.</p>

<form action="/accion/mercado/puja?ID=1" method="post">
<p><span class="amarillo"><b><input type="text" name="puja" value="' . $puja_minima . '" size="2" maxlength="6" style="text-align:right;" /> '.MONEDA.' &nbsp; <input type="submit" value="Pujar" /></b></span></p>
</form>

<table border="0" cellspacing="2" cellpadding="0" class="pol_table">
<tr>
<th>'.MONEDA.'</th>
<th>Ciudadano</th>
<th><a href="/mercado/frase/">Actualizar</a></th>
<th><b>' . $queda . '</b></th>
</tr>';

	$ganador = 'ok';
	$result = mysql_query_old("SELECT user_ID, pols, time,
(SELECT nick FROM users WHERE ID = pujas.user_ID LIMIT 1) AS nick
FROM pujas
WHERE pais = '".PAIS."' AND mercado_ID = 1
ORDER BY pols DESC
LIMIT 15", $link);
	while($row = mysqli_fetch_array($result)) {
		if ($ganador == 'ok') { $ganador = '<b>(Ganador)</b>'; } else { $ganador = false; }
		echo '<tr><td align="right"><b style="font-size:20px;">' . pols($row['pols']) . '</b></td><td>' . crear_link($row['nick']) . '</td><td>' . duracion(time() - strtotime($row['time'])) . '</td><td>' . $ganador . '</td></tr>';
	}


	if (!$puja_minima) { $puja_minima = 1; }
	echo '</table>';

}

//THEME
$txt_title = 'Mercado - ' . $_GET[1];
$txt_nav = array('Mercado');
$txt_menu = 'econ';

?>