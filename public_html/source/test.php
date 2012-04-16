<?php 
include('inc-login.php');
include('inc-functions-accion.php');
if ($pol['user_ID'] != 1) { exit; }
$txt .= '<h1>TEST</h1><hr />';



function crono($new='') {
	global $crono;
	$the_ms = num((microtime(true)-$crono)*1000);
	$crono = microtime(true);
	return '<h3>'.$the_ms.'ms '.$new.'</h3></hr>';
}


$txt .= crono('start');

$sql = "SELECT * FROM users";

for ($i=1;$i<=10;$i++) {

	$result = mysql_query($sql, $link);
	while ($r = mysql_fetch_array($result)) { 
		$ok = $r['nick'];
	}


	$txt .= crono('old');


	// mysql_unbuffered_query
	$result = sql($sql);
	while($r=r($result)){
		$ok = $r['nick'];
	}


	$txt .= crono('new');



}

$txt_title = 'Test';
$txt_nav = array('Test');
include('theme.php');
?>