<?php # maxsim.tech — MIT License — Copyright (c) 2005 Javier González González <gonzo@virtualpol.com>



$test_fail_total_num = 0;

$test_files = glob('{*,*/*,*/*/*,*/*/*/*,*/*/*/*/*}.{test.php,phpt}', GLOB_BRACE);

foreach ($test_files AS $test_file) {
	
	$name = basename($test_file);
	
	//$test_fail_num = intval(sql_key_value('test_last_'.$name));
    $test_fail_num = 0;

	$nav_tabs_li[$name] = '
        <a href="'.$maxsim['app_url'].'?file='.urlencode($test_file).'" 
        style="text-decoration:none;padding-top:3px;padding-bottom:3px;'.($test_file==($_GET['file'] ?? null)?'font-weight:bold;':'').'" title="'.$test_file.'">'.$name.'</a>

        <div class="test-box-num" style="background-color:'.($test_fail_num===0?'blue':'red').';">'.($test_fail_num===0?'':$test_fail_num).'</div>
        <br />';
	
	$test_fail_total_num += $test_fail_num;
}

echo '<table width="100%" border=0><tr><td valign=top align=right style="min-width:200px; padding:20px 10px 0 0;">


<div class="box p-3">

<div class="test-box-num" style="font-size:20px;width:100%;height:75px;background-color:'.($test_fail_total_num===0?'blue':'red').';">
<div style="position: relative; top: 50%; -webkit-transform: translateY(-50%); -ms-transform: translateY(-50%); transform: translateY(-50%);">'.($test_fail_total_num===0?'ALL PASS':$test_fail_total_num).'</div>
</div><br />
    
'.implode("\n", $nav_tabs_li).'
		
<br />
</div>

</td><td valign=top width="100%" style="padding:20px 10px 0 0;">';
		
if ($_GET['file'] ?? null)
    echo '<div class="box p-3">
        <iframe id="test-iframe" src="'.$maxsim['app_url'].'/exec?file='.urlencode($_GET['file']).'" frameborder="0" style="border:none; height:90vh; width:100%;"></iframe>
        </div>';

echo '</td></tr></table>';