<?php # POL.VirtualPol.com — Copyright (c) 2008 Javier González González <gonzo@virtualpol.com> — MIT License 


if ($_GET[2] AND isset($pol['user_ID'])) {
	$comprobante_full = $_GET[2];
	$ref_ID = explodear('-', $comprobante_full, 0);
	$comprobante = explodear('-', $comprobante_full, 1);
	redirect('/votacion/'.$ref_ID.'/verificacion#'.$comprobante);
}