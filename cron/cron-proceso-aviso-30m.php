<?php 



foreach ($vp['paises'] AS $pais) {
	if ($pais != '15M') {
		evento_chat('<b>[#]</b> Quedan <b>30 minutos</b> para el proceso diario.', '0', 0, false, 'e', $pais);
	}
}