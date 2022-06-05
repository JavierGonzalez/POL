<?php # maxsim.tech — MIT License — Copyright (c) 2005 Javier González González <gonzo@virtualpol.com>


if (substr($_GET['file'],0,1) === '/')
    exit;

if (substr($_GET['file'],-9,9) !== '.test.php' AND substr($_GET['file'],-5,5) !== '.phpt')
    exit;

if (!file_exists($_GET['file']))
    exit;


header('Content-Encoding: none'); // Gzip disabled for real-time stream output.
    
echo '
<html>
<body>

<div style="display:flex;flex-direction:column-reverse;height:100%;overflow-y:scroll;">


<table style="margin-top:1000px;">';

$crono_all = microtime(true);

// Maxsim autoload
maxsim_autoload(glob(dirname($_GET['file']).'/*'));
foreach ($maxsim['autoload'] AS $file)
    if (substr($file, -4) === '.php')
        include_once($file);

        
if (substr($_GET['file'],-9,9) === '.test.php')
    include($_GET['file']);

if (substr($_GET['file'],-5,5) === '.phpt')
    test_phpt($_GET['file']);



$crono_all = microtime(true) - $crono_all;


$unit_test['tests_fail'] = ($unit_test['tests_total'] - $unit_test['tests_pass']);

# TODO: sql_key_value('test_last_'.$_GET[1], $unit_test['tests_fail']);


if ($unit_test['tests_fail'] == 0)
	$test_result_print = '<b style="color:blue;">ALL PASS</b>';
else
	$test_result_print = '<b style="color:red;">FAIL &nbsp; '.num($unit_test['tests_fail']).'</b>';

echo '

<tr>
<td colspan="8">
    <hr />
    <div style="font-size:50px;">
        '.$test_result_print.' 
        <span style="margin-left:400px;font-size:20px;">'.num($unit_test['tests_total']).' tests executed in '.num($crono_all * 1000, 1) . ' ms</span>
    </div>
</td>
</tr>

</table>

</div>
</body>
</html>
';
	
	
exit;