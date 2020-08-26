<?php # maxsim.tech — Copyright (c) 2020 Javier González González <gonzo@virtualpol.com> — MIT License


$maxsim = [
    'version'  => '0.5.3',
    'debug'    => ['crono_start' => hrtime(true)],
    ];

maxsim_router($_SERVER['REQUEST_URI']);

maxsim_get($_SERVER['REQUEST_URI']);

ob_start();

foreach ((array)$maxsim['autoload'] AS $file) {
    $ext = pathinfo($file, PATHINFO_EXTENSION);

    if ($ext==='php')
        include($file);

    else if ($ext==='css' OR $ext==='js')
        $maxsim['template']['autoload'][$ext][] = '/'.$file;

    else if ($ext==='ini')
        if ($key_name = basename(str_replace('*', '', $file), '.ini'))
            ${$key_name} = parse_ini_file($file, true, INI_SCANNER_TYPED);
    
    else if ($ext==='json')
        if ($key_name = basename(str_replace('*', '', $file), '.json'))
            ${$key_name} = (array)json_decode(file_get_contents($file), true);
}


include($maxsim['app']); // What user want.


if ($maxsim['output']==='text') {
    header('Content-Type:text/plain; charset=utf-8');

} else if ($maxsim['output']==='json' AND is_array($echo)) {
    ob_end_clean();
    header('Content-type:application/json; charset=utf-8');
    echo json_encode((array)$echo, JSON_PRETTY_PRINT);

} else if (is_string($maxsim['output'])) {
    $echo = ob_get_contents();
    ob_end_clean();
    header('Content-Type:text/html; charset=utf-8');
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
            if ($id!==0 AND basename($e)==='index.php')
                $maxsim['app'] = $e;

        foreach ($ls AS $e)
            if (basename($e)===$levels[$id+1].'.php')
                $maxsim['app'] = $e;
    }

    if (!$maxsim['app']) {
        header('HTTP/1.0 404 Not Found');
        if (file_exists('404.php'))
            $maxsim['app'] = '404.php';
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


function maxsim_absolute(string $dir) {
    return (string) str_replace($_SERVER['DOCUMENT_ROOT'].'/', '', $dir).'/'; // Refact.
}