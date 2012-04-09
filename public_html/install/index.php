<?php
if(!isset($_GET['step'])){ header("Location: ?step=0"); }

include("common.php");

$theme->add("header");

switch($_GET['step']){
	case 0:
		$theme->add("step0");
		break;
	case 1:
		$theme->add("step1");
		break;
	default:
		header("Location: ?step=0");
}

$theme->add("footer");



?>
