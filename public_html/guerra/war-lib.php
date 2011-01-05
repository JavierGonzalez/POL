<?php
function mostrar_cuadricula(){
	if (isset($_GET['cuad'])) {
		if ($_GET['cuad'] == "no") {
			echo "style='visibility:hidden'";
		}
	}
}
function mostrar_noms($i){
	if (isset($_GET['noms'])) {
		if ($_GET['noms'] == "no") {
			if ($i == 0){
				echo "style='visibility:hidden'";
			}
			else {
				return 1;
			}
		}
	}
}
function noms_hab($i){
	if ($i == 1){
		echo "habilitar nombres";
	}
	else {
		echo "deshabilitar nombres";
	}
}
?>