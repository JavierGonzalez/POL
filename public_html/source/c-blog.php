<?php 
include('inc-login.php');
/*
http://pol.teoriza.com/blog/
http://pol.teoriza.com/blog/nombre/
http://pol.teoriza.com/blog/nombre/admin/
http://pol.teoriza.com/blog/nombre/123/nombre-post/
http://pol.teoriza.com/blog/nombre/rss/

pol_blog		(blog_ID, url, user_ID, acceso, nombre, titulo, descripcion, time, time_last, tipo, estado)
pol_blog_post	(post_ID, blog_ID, user_ID, url, titulo, texto, tags, num_com, estado)
pol_blog_com	(com_ID, post_ID, blog_ID, user_ID, nick, time, texto, estado)
*/


if ($_GET['b'] == 'admin') {		// BLOG ADMIN
	$txt .= 'blog admin';



} elseif ($_GET['b'] == 'rss') {	// BLOG RSS
	$txt .= 'blog rss';



} elseif ($_GET['c']) {				// BLOG POST
	$txt .= 'blog post';



} elseif ($_GET['a']) {				// BLOG HOME
	
	$re = mysql_query("SELECT ID_estudio, 
(SELECT nombre FROM ".SQL."estudios WHERE ".SQL."estudios.ID = ID_estudio LIMIT 1) AS nombre,
(SELECT nivel FROM ".SQL."estudios WHERE ".SQL."estudios.ID = ID_estudio LIMIT 1) AS nivel
FROM ".SQL."estudios_users  WHERE estado = 'ok' AND cargo = '1' AND user_ID = '" . $pol['user_ID'] . "'
ORDER BY nivel DESC", $link);
	while($r = mysql_fetch_array($re)){
		$txt .= 'blog home';
	}

} else {							// HOME
	$txt .= 'home';



}



//THEME
include('theme.php');
?>
