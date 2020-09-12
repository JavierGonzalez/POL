<?php # maxsim.tech — Copyright (c) 2020 Javier González González <gonzo@virtualpol.com> — MIT License


define('crono_start', hrtime(true));

$maxsim['version'] = '0.5.4';

maxsim_router($_SERVER['REQUEST_URI']);

maxsim_get($_SERVER['REQUEST_URI']);

ob_start();

foreach ((array)$maxsim['autoload'] AS $file) {
    $ext = pathinfo($file, PATHINFO_EXTENSION);

    if ($ext==='php')
        include($file);

    else if ($ext==='js' OR $ext==='css')
        $maxsim['template']['autoload'][$ext][] = '/'.$file;

    else if ($ext==='ini')
        if ($key_name = basename(str_replace('*', '', $file), '.'.$ext))
            define($key_name, (array)parse_ini_file($file, true, INI_SCANNER_TYPED));
    
    else if ($ext==='json')
        if ($key_name = basename(str_replace('*', '', $file), '.'.$ext))
            ${$key_name} = (array)json_decode(file_get_contents($file), true);
}


include($maxsim['app']);


if ($maxsim['output']==='text') {
    header('content-Type: text/plain');

} else if ($maxsim['output']==='json' AND is_array($echo)) {
    ob_end_clean();
    header('content-type: application/json');
    echo json_encode((array)$echo, JSON_PRETTY_PRINT);

} else if (is_string($maxsim['output'])) {
    $echo = ob_get_contents();

    if ($echo==='') {
        http_response_code(404);
        if (file_exists('404.php')) {
            include('404.php');
            $echo = ob_get_contents();
        } else {
            $echo = 'Error 404: not found.';
        }
    }

    ob_end_clean();
    include($maxsim['output'].'/index.php');
}

exit;


function maxsim_router(string $uri) {
    global $maxsim;

    $url = explode('?', $uri)[0];
    if ($url==='/')
        $url = '/index';

    $levels = explode('/', $url);

    foreach ($levels AS $id => $level) {
        $path[] = $level;

        if (!$ls = glob(($id===0?'*':implode('/', array_filter($path)).'/*'))) // Refact
            break;

        foreach (maxsim_autoload($ls) AS $file)
            $maxsim['autoload'][] = $file;
        
        foreach ($ls AS $e)
            if (basename($e)==='index.php')
                $maxsim['app'] = $e;

        foreach ($ls AS $e)
            if (basename($e)===$levels[$id+1].'.php')
                $maxsim['app'] = $e;
    }
}


function maxsim_autoload(array $ls, $load_prefix=false) {

    foreach ($ls AS $e)
        if (preg_match('/\.(php|js|css|ini|json)$/', $e))
            if ($load_prefix OR substr(basename($e), 0, 1)==='*')
                $autoload[] = $e;

    foreach ($ls AS $e)
        if (!fnmatch('*.*', $e))
            if (substr(basename($e), 0, 1)==='*')
                if ($ls_recursive = glob(str_replace('*', '\*', $e).'/*'))
                    foreach (maxsim_autoload($ls_recursive, true) AS $file)
                        $autoload[] = $file;

    return (array) $autoload;
}


function maxsim_get(string $uri) {
    global $_GET, $maxsim;

    $app_level = count(explode('/', $maxsim['app']))-1;

    $url = explode('?', $uri)[0];
    if ($url==='/')
        $url .= 'index';

    $levels = array_filter(explode('/', $url));
    foreach ($levels AS $level => $name)
        if ($level-$app_level > 0)
            $levels_relative[$level-$app_level] = $name;

    $_GET = array_merge((array)$levels_relative, $_GET);

    if ($_GET[0]==='maxsim')
        exit($maxsim['version']);
}