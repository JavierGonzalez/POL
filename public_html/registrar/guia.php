<?php
	$i = 0;
	if (isset($_GET['p'])) {
		if ($_GET['p'] <= 1) {
			$i = 1;
		}
		elseif ($_GET['p'] >= 9){
			$i = 9;
		}
		else {
			$i = $_GET['p'];
		}
	}
	else {
		$i = 1;
	}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Guia de registro</title>
<link rel="stylesheet" type="text/css" href="style.css" />
</head>
<body>
<img src="guia_<?php echo $i; ?>.png" />
<div id="atras"><a href="./guia.php?p=<?php $i = $i - 1; echo $i; ?>" ><img src="atras.png" /></a></div>
<div id="siguiente"><a href="./guia.php?p=<?php $i = $i +2; echo $i; ?>" ><img src="siguiente.png" /></a></div>
</body>
</html>