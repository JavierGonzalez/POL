<?php # maxsim.tech — MIT License — Copyright (c) 2005-2020 Javier González González <gonzo@virtualpol.com>


function ___($echo='', $echo2=false, $scroll_down=false) {
	global $maxsim;

    if (!isset($maxsim['debug']['crono']))
        $maxsim['debug']['crono'] = $_SERVER['REQUEST_TIME_FLOAT'];

    $microtime = $maxsim['debug']['crono'];

    echo '<br />'."\n";
    echo @++$maxsim['debug']['count'].'. &nbsp; <span title="'.date('Y-m-d H:i:s').'">'.implode(' &nbsp; ', profiler($microtime)).'</span> &nbsp; ';

    if (is_array($echo2)) {
        echo $echo;
        echo '<xmp class="box p-3">'.print_r($echo2, true).'</xmp>';
    } else if (is_string($echo)) {
        echo $echo;
    } else if (is_array($echo) OR is_object($echo)) {
        echo '<xmp class="box p-3">'.print_r($echo, true).'</xmp>';
    } else {
        var_dump($echo);
    }

    if ($scroll_down) {
        if ($maxsim['debug']['count']==1) {
            if (function_exists('apache_setenv'))
                @apache_setenv('no-gzip', 1);

            ob_end_flush();
            echo '<script>function __sd() { window.scrollTo(0,document.body.scrollHeight); }</script>';
        }

        echo '<script>__sd();</script>';
        ob_flush();
    }

    $maxsim['debug']['crono'] = microtime(true);
}


function profiler($microtime=false) {
    global $maxsim;

    if (!$microtime)
        $microtime = $_SERVER['REQUEST_TIME_FLOAT'];

    $output[] = number_format((microtime(true)-$microtime)*1000,2).' ms';
    
    if ($maxsim['debug']['sql']['count'] ?? null)
        $output[] = number_format($maxsim['debug']['sql']['count']).' sql';
    
    if ($maxsim['debug']['rpc']['count'] ?? null)
        $output[] = number_format($maxsim['debug']['rpc']['count']).' rpc';

    $output[] = number_format(memory_get_usage(false)/1024).' kb';
    
    return $output;
}



function maxsim_timing() {
    global $maxsim;
    

    if ($maxsim['debug']['timing']['app'] ?? null)
        $maxsim['debug']['timing']['template'] = microtime(true);
    else
        $maxsim['debug']['timing']['app'] = microtime(true);
    
    
    $microtime_last = $_SERVER['REQUEST_TIME_FLOAT'];
    
    $debug_log_target = ['time' => time()];

    $id = 0;



    $server_timing = [];
    foreach ((array) $maxsim['debug']['timing'] AS $key => $value) { 

        $desc = '';
        if ($key === 'maxsim') {
            $desc = ' ('.$maxsim['maxsim_version'].')';
        } else if ($key === 'router') {
            $desc = ' ('.$maxsim['debug']['ls'].' ls, '.count($maxsim['events']).' events)';
        } else if ($key === 'autoload') {
            $autoload_files_php = 0;
            foreach ($maxsim['autoload'] AS $file)
                if (substr($file, -4) === '.php')
                    $autoload_files_php++;
            $desc = ' ('.$autoload_files_php.' php)';
        } else if ($key === 'template') {
            $autoload_files_js = 0;
            $autoload_files_css = 0;
            foreach ($maxsim['autoload'] AS $file) {
                if (substr($file, -3) === '.js')
                    $autoload_files_js++;
                if (substr($file, -4) === '.css')
                    $autoload_files_css++;
            }
            $desc = ' ('.$autoload_files_js.' js, '.$autoload_files_css.' css)';
        } else if ($key === 'sql') {
            $desc = ' ('.$maxsim['debug']['sql']['count'].' queries)';
        }

        if ($value > 1000000000) {
            $debug_log_target[$key] = round(($value-$microtime_last)*1000, 2);
            $server_timing[] = ++$id.';dur='.$debug_log_target[$key].';desc="'.$key.$desc.'"';
            $microtime_last = $value;
        } else {
            $debug_log_target[$key] = round($value,2);
            $server_timing[] = $key.';dur='.$debug_log_target[$key].';desc="'.strtoupper($key).$desc.'"';
        }
    }

    $debug_log_target['RAM'] = number_format(memory_get_usage(false)/1024);
    $debug_log_target['TOTAL'] = round((microtime(true)-$_SERVER['REQUEST_TIME_FLOAT'])*1000, 2);
    
    
    $server_timing[] = '99;desc="RAM ('.$debug_log_target['RAM'].' kb)"';
    $server_timing[] = 'TOTAL;dur='.$debug_log_target['TOTAL'];
    
    $debug_log_target['url'] = $_SERVER['REQUEST_URI'];
    
    if (false AND http_response_code() === 200 AND file_exists('maxsim/log/app/') AND is_writable('maxsim/log/app/')) {
        chdir($_SERVER['DOCUMENT_ROOT']); // Working directory of the script can change inside the shutdown function under some web servers, e.g. Apache.    
        file_put_contents('maxsim/log/app/'.str_replace('/', '|', $maxsim['app']).'.log', json_encode($debug_log_target)."\n", FILE_APPEND);
    }

    header('server-timing: '.implode(', ', (array)$server_timing));
}
header_register_callback('maxsim_timing');
