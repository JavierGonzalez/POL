<?php
/*
### ANIMAL CAPTCHA 1.4
Author: GONZO (Javier Gonzalez, gonzomail@gmail.com)
url: http://gonzo.teoriza.com/animal-captcha
Powered by: Blogs Teoriza (www.Teoriza.com)
2009/11/20
###
*/


// *** CONFIG ***

$ac_dir = 'animal/';		// images directory of animals or things
$width = 120;
$jpg_quality = 76; 			// final jpg quality, recomended: 76
$ac_num = 2;				// number of animals/objects (1 normal, 2 default, 4 max)
$bg_trans = rand(32,35); 	// transparency of background image, recomended: rand(30,35)

$polygons_num = false; 		// number of random polygons, false to disable, recomended: false
$polygons_trans = false;	// transparency of polygons, recomended: rand(30,40)



// *** CORE ***

$height = $width;

// gen images list
chdir($ac_dir);
$dir = opendir('.');
while (($file = readdir($dir)) !== false) {
	$ext = explode('.', $file);
	if (($ext[1] == 'jpg') && (is_file($file))) { $files[] = $file; } 
}
closedir($dir);
chdir('../');

// load N animals images
$img_final = imagecreatetruecolor($width*$ac_num, $height);
$ac_rand = array_rand($files, ($ac_num+1));

for ($i=1;$i<=$ac_num;$i++) {
	// select primary animal image
	$an = explode('.', $files[$ac_rand[$i]]);
	$an = $an[0]; 
	$a_name = explode('_', $an);
	if (isset($ac_result)) { $ac_result .= '|'; }
	$ac_result .= $a_name[0];
	$img = imagecreatefromjpeg($ac_dir.$an.'.jpg');
	imagecopy($img_final, $img, $width*($i-1), 0, 0, 0, $width, $height);
	imagedestroy($img);
}

// random background image
$ac_bg = $files[$ac_rand[0]];
$img_bg_size = imagecreatetruecolor($width*$ac_num, $height);
$img_bg = imagecreatefromjpeg($ac_dir.$ac_bg);
$img_bg = imagerotate($img_bg, (90*rand(1,3)), -1); // invert random background animal
imagecopyresized($img_bg_size, $img_bg, 0, 0, 0, 0, $width*$ac_num, $height, $width, $height);
imagedestroy($img_bg);
imagecopymerge($img_final, $img_bg_size, 0, 0, 0, 0, $width*$ac_num, $height, $bg_trans);
imagedestroy($img_bg_size);

// random transparent polygons
if ($polygons_num) {
	for ($i=0;$i<$polygons_num;$i++) {
		$c_min = 200; $c_max = 255;
		$polygon_color = imagecolorallocatealpha($img_final, rand($c_min, $c_max), rand($c_min, $c_max), rand($c_min, $c_max), $polygons_trans);
		$wf1 = $width*$ac_num;
		imagefilledpolygon($img_final, 
		array(rand($wf1,$wf1), rand(-0*$wf1,$wf1), rand(-0*$wf1,$wf1), rand(-0*$wf1,$wf1), rand(-0*$wf1,$wf1), rand(-0*$wf1,$wf1),), 3, $polygon_color);
	}
}

// set session result
if (!isset($_SESSION)) { session_start(); }
$_SESSION['animalcaptcha'] = $ac_result;


// print image and destroy
header('Content-type: image/jpeg');
imagejpeg($img_final, NULL, $jpg_quality);
imagedestroy($img_final);



?>