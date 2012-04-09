<?php
if(!isset($_GET['step'])){ header("Location: ?step=0"); }

include("common.php");

$theme->add("header");

switch($_GET['step']){
	case 0:
		
		if(isset($_POST['dominio'])){
			echo "enviado";

			if(0){
				header("Location: ?step=1");
			}
		}


		
		if(isset($_SESSION['install-dominio'])){
			$theme->addvar("{DOMINIO}",$_SESSION['install-dominio']);
		}else{
			$theme->addvar("{DOMINIO}",$_SERVER['REMOTE_HOST']);
		}
		$theme->addvar("{TITLE}","test");
		$theme->add("step0");
		break;
		

	case 1:
		$theme->add("step1");
		break;
	default:
		header("Location: ?step=0");
}

$theme->add("footer");
echo $theme->return_html();


?>
