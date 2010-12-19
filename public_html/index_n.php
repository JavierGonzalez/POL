<?php
        include('inc-login.php');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>VirtualPOL t2</title>
<link rel="stylesheet" type="text/css" href="style_n.css" />
</head>
<body>
<table id="tabla" cellspacing="10px">
  <tr>
    <td colspan="2" id="tit"><img src="titulo.png" /></td>
    <td id="espai">
	<?php echo '<a href="http://' . strtolower($pol['pais']) . '.virtualpol.com/perfil/' . $pol['nick'] . '/">' . $pol['nick'] . '</a>'; ?><br />
	<?php echo '<a href="http://' . strtolower($pol['pais']) . '.virtualpol.com/pols/">Dinero</a>'; ?>	<br />
	<?php echo '<a href="http://' . strtolower($pol['pais']) . '.virtualpol.com/msg/">Mensajes</a>'; ?><br />
	<a href="http://www.virtualpol.com/registrar/login.php?a=logout">Cerrar sesión</a></td>
  </tr>
  <tr>
    <td id="pol" class="noms"><a href="http://pol.virtualpol.com/">
			<b>POL</b>
			<br />República democrática
		</a></td>
    <td id="hispania" style="background:url(hispania.png)">&nbsp;</td>
    <td id="atlantis" class="noms"><a href="http://atlantis.virtualpol.com/">
		<b>Atlantis</b><br />República Liberal-Demócrata</a></td>
  </tr>
  <tr>
    <td id="pol" style="background:url(pol.png)">&nbsp;</td>
    <td id="hispania" class="noms"><a href="http://hispania.virtualpol.com/">
		<b>Hispania</b><br />República democrática</a></td>
    <td id="atlantis" style="background:url(atlantis.png)">&nbsp;</td>
  </tr>
  <tr>
    <td id="estadisticas"><a href="http://virtualpol.com/">Población</a><br /><a href="http://pol.virtualpol.com/info/economia/">+Estadisticas</a></td>
    <td colspan="2" id="ciudadanos"><?php
$time_pre = date('Y-m-d H:i:00', time() - 1800); // 30 minutos
$result = mysql_query("SELECT nick, pais, estado
FROM ".SQL_USERS." 
WHERE fecha_last > '" . $time_pre . "' AND estado != 'desarrollador' AND estado != 'expulsado'
ORDER BY fecha_last DESC", $link);
while($row = mysql_fetch_array($result)){ 
        $li_online_num++; 
        $gf['censo_online'][$row['pais']]++;


        $pais_url = strtolower($row['pais']);
        if ($pais_url == 'ninguno') { $pais_url = 'pol'; }
        $li_online .= ' <a href="http://'.$pais_url.'.virtualpol.com/perfil/'.$row['nick'].'/">'.$row['nick'].'</a>'; 
}




echo "Ciudadanos online:" . $li_online;
?></td>
  </tr>
</table>
</body>
</html>
