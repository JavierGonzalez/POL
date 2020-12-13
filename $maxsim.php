<?php # maxsim.tech — MIT License — Copyright (c) 2005 Javier González González <gonzo@virtualpol.com>

maxsim:

$maxsim['version'] = '0.5.10';

ob_start();

maxsim_router();
maxsim_get();

foreach ((array) $maxsim['autoload'] AS $file) {
    $ext = pathinfo($file, PATHINFO_EXTENSION);

    if ($ext==='php')
        include_once($file);

    else if ($ext==='ini')
        if ($key = ltrim(basename($file, '.'.$ext), '+'))
            define($key, (array)parse_ini_file($file, true, INI_SCANNER_TYPED));
    
    else if ($ext==='json')
        if ($key = ltrim(basename($file, '.'.$ext), '+'))
            ${$key} = (array)json_decode(file_get_contents($file), true);
}


include_once($maxsim['app']); #


if (isset($maxsim['redirect'])) {
    $_SERVER['REQUEST_URI'] = $maxsim['redirect'];
    unset($maxsim['redirect']);
    goto maxsim;
}


if (isset($maxsim['output']) AND $maxsim['output']==='text')
    header('content-Type: text/plain');

else if (isset($maxsim['output']) AND $maxsim['output']==='json' AND is_array($echo)) {
    ob_end_clean();
    header('content-type: application/json');
    echo json_encode((array)$echo, JSON_PRETTY_PRINT);

} else if (isset($maxsim['output']) AND file_exists($maxsim['output'].'/index.php')) {
    $echo = ob_get_contents();
    ob_end_clean();

    if ($echo==='') {
        http_response_code(404);
        $echo = (is_string($maxsim['template'][404])?$maxsim['template'][404]:'Error 404: NOT FOUND.');
    }

    include($maxsim['output'].'/index.php');
}

exit;


function maxsim_router() {
    global $maxsim;

    $levels = explode('/', explode('?', $_SERVER['REQUEST_URI'])[0]);

    $maxsim['autoload'] = [];
    foreach ($levels AS $id => $level) {
        $path[] = $level;

        if (!$ls = glob(($id?implode('/', array_filter($path)).'/':'').'*'))
            break;

        maxsim_autoload($ls);

        foreach ($ls AS $file)
            if (basename($file)==='index.php')
                $maxsim['app'] = $file;

        foreach ($ls AS $file)
            if (isset($levels[$id+1]) AND basename($file)===$levels[$id+1].'.php')
                $maxsim['app'] = $file;
    }
}


function maxsim_autoload(array $ls, bool $autoload_files=false) {
    global $maxsim;

    foreach ($ls AS $file)
        if (preg_match('/\.(php|js|css|ini|json)$/', basename($file)))
            if (!isset($maxsim['autoload']) OR !in_array($file, (array)$maxsim['autoload']))
                if ($autoload_files OR substr(basename($file),0,1)==='+')
                    $maxsim['autoload'][] = $file;

    foreach ($ls AS $dir)
        if (!fnmatch('*.*', basename($dir)))
            if (substr(basename($dir),0,1)==='+')
                maxsim_autoload(glob($dir.'/*'), true);
}


function maxsim_get() {
    global $_GET, $maxsim;

    $app_level = count(explode('/', $maxsim['app']))-1;
    
    $url = explode('?', $_SERVER['REQUEST_URI'])[0];
    
    if (substr($maxsim['app'],-9)==='index.php')
        $url = '/index'.$url;

    $id = 0;
    foreach (array_filter(explode('/', $url)) AS $level => $value)
        if ($level-$app_level > 0)
            $_GET[$id++] = $value;
}






 