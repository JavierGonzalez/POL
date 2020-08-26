<?php # POL.VirtualPol.com — Copyright (c) 2008 Javier González González <gonzo@virtualpol.com> — MIT License 


// SQL+XSS INYECTION PROTECTION
foreach ($_GET     AS $key => $value)      $_GET[$key] = escape($value);
foreach ($_POST    AS $key => $value)     $_POST[$key] = escape($value);
foreach ($_REQUEST AS $key => $value)  $_REQUEST[$key] = escape($value);
foreach ($_COOKIE  AS $key => $value)   $_COOKIE[$key] = escape($value);

foreach ($_SERVER  AS $key => $value)   
	if (!in_array($key, [
			'GATEWAY_INTERFACE',
			'SERVER_ADDR',
			'SERVER_SOFTWARE',
			'DOCUMENT_ROOT',
			'SERVER_ADMIN',
			'SERVER_SIGNATURE',
			]))
		$_SERVER[$key] = escape($value);



function escape($a) {

	// SQL MITIGATION
	$a = nl2br($a);
	$a = str_replace("'", '&#39;', $a);
	$a = str_replace('"', '&quot;', $a);
	$a = str_replace(array("\x00", "\x1a"), '', $a);
	
	//////////////
	$a = e($a); // SQL INYECTION PREVENTION
	//////////////

	// XSS
	$js_filter = 'video|javascript|vbscript|expression|applet|xml|blink|script|embed|object|iframe|frame|frameset|ilayer|bgsound|onabort|onactivate|onafterprint|onafterupdate|onbeforeactivate|onbeforecopy|onbeforecut|onbeforedeactivate|onbeforeeditfocus|onbeforepaste|onbeforeprint|onbeforeunload|onbeforeupdate|onblur|onbounce|oncellchange|onchange|onclick|oncontextmenu|oncontrolselect|oncopy|oncut|ondataavailable|ondatasetchanged|ondatasetcomplete|ondblclick|ondeactivate|ondrag|ondragend|ondragenter|ondragleave|ondragover|ondragstart|ondrop|onerror|onerrorupdate|onfilterchange|onfinish|onfocus|onfocusin|onfocusout|onhelp|onkeydown|onkeypress|onkeyup|onlayoutcomplete|onload|onlosecapture|onmousedown|onmouseenter|onmouseleave|onmousemove|onmouseout|onmouseover|onmouseup|onmousewheel|onmove|onmoveend|onmovestart|onpaste|onpropertychange|onreadystatechange|onreset|onresize|onresizeend|onresizestart|onrowenter|onrowexit|onrowsdelete|onrowsinserted|onscroll|onselect|onselectionchange|onselectstart|onstart|onstop|onsubmit|onunload';
	$a = preg_replace('/(<|&lt;|&#60;|&#x3C;|&nbsp;)('.$js_filter.')/', 'nojs1', $a);
	$a = str_replace(array('accion.php', 'ajax.php','/action','/ajax'), 'nojs2', $a);
	
	return $a;
}