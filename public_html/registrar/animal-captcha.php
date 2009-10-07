<?php
/*
### ANIMAL CAPTCHA 1.2
Author: GONZO (Javier Gonzalez Gonzalez) gonzo.teoriza.com
Info: http://gonzo.teoriza.com/2008/04/30/animal-captcha-10-script-php-que-general-captcha-grafico-con-animales/
Blogs Teoriza (www.Teoriza.com)
2008/12/25
###
*/


// *** CONFIG ***

$dir_animals = 'animal/'; // images directory of animals or things
$width = 120;
$height = 120;
$jpg_quality = 70; 		// final jpg quality (good:90, poor: 50), recomended: 70

$polygons_num = 1; 		// number of random polygons, recomended: 0-2
$polygons_trans = mt_rand(30,40);	// transparency of polygons, recomended: mt_rand(30,40)
$img_bg_trans = mt_rand(10,20); 	// transparency of background image, recomended: mt_rand(25,35)
$img_rotate = mt_rand(-5,5); 	// random rotate image, false to disable, recomended: mt_rand(-7,7)



// *** START CODE ***

if (!isset($_SESSION)) { session_start(); }

//Select one random image name
chdir($dir_animals);
$dir = opendir('.');
while (($file = readdir($dir)) !== false) {
	if (($file != '.') && ($file != '..') && (is_file($file)) && ($file != 'index.html')) { $files[] = $file; }
}
closedir($dir);
chdir('../');
srand((float) microtime() * 10000000);
$file = array_rand($files);
$animal = explode('.', $files[$file]);
$animal = $animal[0]; // select primary animal image

//for develop
//if ($_GET['animal']) { $animal = $_GET['animal']; }

$a_name = explode("_", $animal);
$_SESSION['animalcaptcha'] = $a_name[0]; // true answer random of the image, format: 'name-othername-othername'

$animal_bg = array_rand($files);
$animal_bg = $files[$animal_bg]; // select secundary animal image, for background



// load random image
$img = imagecreatefromjpeg($dir_animals . $animal . '.jpg');

// random transparent polygons
for ($i=0;$i<$polygons_num;$i++) {
	$c_min = 200; $c_max = 255;
	$polygon_color = imagecolorallocatealpha($img, mt_rand($c_min, $c_max), mt_rand($c_min, $c_max), mt_rand($c_min, $c_max), $polygons_trans);
imagefilledpolygon($img, array(
	mt_rand(-0*$width,$width*1), mt_rand(-0*$width,$width*1),
	mt_rand(-0*$width,$width*1), mt_rand(-0*$width,$width*1),
	mt_rand(-0*$width,$width*1), mt_rand(-0*$width,$width*1),
), 3, $polygon_color);
}

// load random background image
$img_bg = imagecreatefromjpeg($dir_animals . $animal_bg);
imagecopymerge($img, $img_bg, 0, 0, 0, 0, $width, $height, $img_bg_trans);

// random rotate
if ($img_rotate) { $img = imagerotate($img, $img_rotate, -1); }

//print $img and destroy
header("Content-type: image/jpeg");
imagejpeg($img, NULL, $jpg_quality);
imagedestroy($img);
?>
