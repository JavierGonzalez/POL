<?php
include('inc-login.php');

$txt_title = 'Documentaci&oacute;n de VirtualPol | Manual, ayuda'; 
$txt_header = '<style type="text/css">.content { width:860px; margin: 0 auto; }</style>';


//$txt .= '<iframe style="width:100%;min-height:12000px;border:none;margin-top:-15px;" src="https://docs.google.com/document/pub?id=13Gv-ZnunFydp_rhWZVo0ZRyQZ74j8fP5MV89TQNBO-Q&amp;embedded=true"></iframe>';


$result = mysql_query("SELECT title, text FROM docs WHERE ID = 2 LIMIT 1", $link); // doc_ID 2 = Documentacion
while($r = mysql_fetch_array($result)) { $title = $r['title']; $text = $r['text']; }

$txt .= '<em>'.$txt_legal.'</em>

<div style="color:#555;">
<h1 style="color:#444;text-align:center;font-size:28px;">'.$title.'</h1>

<div id="doc_pad">
'.$text.'
</div>

</div>';



include('theme.php');
?>
