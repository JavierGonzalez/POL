<?php
/*
### ANIMAL CAPTCHA 1.3
Author: GONZO (Javier Gonzalez Gonzalez) gonzomail@gmail.com
url: http://gonzo.teoriza.com/animal-captcha
Blogs Teoriza (www.Teoriza.com)
2009/10/03
###
*/


// *** CONFIG ***

$dir_animals = 'animal/';			// images directory of animals or things
$width = 120;
$height = 120;
$jpg_quality = 90; 					// final jpg quality (good:90, poor: 60), recomended: 90 max

$polygons_num = false; 				// number of random polygons, false to disable, recomended: 0-2
$polygons_trans = mt_rand(30,40);	// transparency of polygons, recomended: mt_rand(30,40)

$img_bg_trans = mt_rand(30,35); 	// transparency of background image, recomended: mt_rand(30,35)
$img_rotate = false; 				// random rotate image, false to disable, recomended: mt_rand(-6,6)



// *** CORE ***

if (!isset($_SESSION)) { session_start(); }

// Select one random image name
chdir($dir_animals);
$dir = opendir('.');
while (($file = readdir($dir)) !== false) {
	if (($file != '.') && ($file != '..') && (is_file($file)) && ($file != 'index.html')) { $files[] = $file; }
}
closedir($dir);
chdir('../');

// select primary animal image
srand((float) microtime() * 10000000);
$file = array_rand($files);
$animal = explode('.', $files[$file]);
$animal = $animal[0]; 
$a_name = explode('_', $animal);
$_SESSION['animalcaptcha'] = $a_name[0]; // true answer random of the image, format: 'name-othername-othername_id-opcional.jpg'

// select secundary animal image, for background
$animal_bg = array_rand($files);
$animal_bg = $files[$animal_bg]; 



// load random image
$img = imagecreatefromjpeg($dir_animals.$animal.'.jpg');

// random transparent polygons
if ($polygons_num) {
	for ($i=0;$i<$polygons_num;$i++) {
		$c_min = 200; $c_max = 255;
		$polygon_color = imagecolorallocatealpha($img, mt_rand($c_min, $c_max), mt_rand($c_min, $c_max), mt_rand($c_min, $c_max), $polygons_trans);
		imagefilledpolygon($img, array(
			mt_rand(-0*$width,$width*1), mt_rand(-0*$width,$width*1),
			mt_rand(-0*$width,$width*1), mt_rand(-0*$width,$width*1),
			mt_rand(-0*$width,$width*1), mt_rand(-0*$width,$width*1),
		), 3, $polygon_color);
	}
}

// load random background image
$img_bg = imagecreatefromjpeg($dir_animals.$animal_bg);
// invert background animal
$img_bg = imagerotate($img_bg, (90*mt_rand(1,3)), -1); 
imagecopymerge($img, $img_bg, 0, 0, 0, 0, $width, $height, $img_bg_trans);

// random rotate
if ($img_rotate) { $img = imagerotate($img, $img_rotate, -1); }

// print image and destroy
header('Content-type: image/jpeg');
imagejpeg($img, NULL, $jpg_quality);
imagedestroy($img);
?>