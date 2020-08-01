<?php # maxsim


function maxsim_template() {
    global $maxsim, $echo;

    if ($maxsim['output']=='plain')
        header('Content-Type: text/plain');
    else if ($maxsim['output']=='json' OR is_array($echo)) {
        ob_end_clean();
        header('Content-type:application/json;charset=utf-8');
        echo json_encode((array)$echo, JSON_PRETTY_PRINT);
    
    } else {
        $echo = ob_get_contents();
        ob_end_clean();
        header('Content-Type:text/html; charset=utf-8');
        include('html/index.php');
    }

}

register_shutdown_function('maxsim_template');