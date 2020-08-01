<?php 



foreach ($vp['paises'] AS $pais) {
	if ($pais != '15M') {
		evento_chat('<b>[#]</b> Quedan <b>15 minutos</b> para el proceso diario.', '0', 0, false, 'e', $pais);
	}
}