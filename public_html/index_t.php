<?php
	include('inc-login.php');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>VirtualPOL t1</title>
<link rel="stylesheet" type="text/css" href="style_t.css" />
</head>
<body>
<div id="menu">
	<?php echo '<a href="http://' . strtolower($pol['pais']) . '.virtualpol.com/perfil/' . $pol['nick'] . '/">' . $pol['nick'] . '</a>' ?>
	<a href="http://www.virtualpol.com/registrar/login.php?a=logout">Cerrar sesión</a>
</div>
<div id="titulo">
	<img src="titulo.png" />
</div>
<div id="container">
	<div class="pais" id="pol"><a href="http://pol.virtualpol.com/">
		<b>POL</b><br />Republica democratica<br /><div class="entrar">Entrar</div>
	</a></div>
	<div class="pais" id="hispania"><a href="http://hispania.virtualpol.com/">
		<b>Hispania</b><br />Republica democratica<br /><div class="entrar">Entrar</div>
	</a></div>
	<div class="pais" id="atlantis"><a href="http://atlantis.virtualpol.com/">
		<b>Atlantis</b><br />Republica Liberal-Democrata<br /><div class="entrar">Entrar</div>
	</a></div>
</div>
<br />
<div id="noms"><?php
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
?>
</div>
</body>
</html>
