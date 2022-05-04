<?php # maxsim.tech — MIT License — Copyright (c) 2005 Javier González González <gonzo@virtualpol.com>


function test_phpt(string $file) {

    $test_operators = ['==', '===', '!=', '!==', '>', '>=', '<', '<=', '<>', '<=>', '='];

    $file_code = file_get_contents($file);

    foreach (explode("\n", $file_code) AS $test_line) {
        $test_line = trim($test_line);


        if (strlen($test_line) === 0)
            continue;

        if (substr($test_line,0,2) === '<?')
            continue;

        if (substr($test_line,0,2) === '# ') {
            test_tr(substr($test_line,2));
            continue;
        }

        if (substr($test_line,0,1) === '#')
            continue;
            
        if (substr($test_line,0,2) === '//')
            continue;
        

        // Select first operator
        $test_rank = [];
        foreach ($test_operators AS $op) {
            $elm = explode(' '.$op.' ', $test_line, 2);
            if (isset($elm[1]))
                $test_rank[$op] = strlen($elm[0]);
        }
        asort($test_rank);
        $operator = array_key_first($test_rank);


        // Line parts
        $test_expected = null;
        if ($operator) {
            $test_code = false;
            $elm = explode($operator, $test_line, 2);
            if (isset($elm[1])) {
                $test_expected  = trim($elm[0]);
                $test_code      = trim(explode(' //', $elm[1])[0]);
            }
            if ($test_code === false)
                continue;
        } else {
            $test_code = $test_line;
        }
        

        // #pass_fail (Inverted test veredict)
        if (strpos($test_code, '#pass_fail') !== false) {
            $test_code = trim(str_replace('#pass_fail', '', $test_code));
            $pass_fail = true;
        } else {
            $pass_fail = false;
        }


        // #limit_ms=1 (FAIL if execution time is more than one milisecond)
        if (strpos($test_code, '#limit_ms') !== false) {
            $ms = explode('#limit_ms=', $test_code)[1];
            $limit_ms = trim(explode(' ', trim($ms))[0]);
            $test_code = trim(str_replace('#limit_ms='.$limit_ms, '', $test_code));
        } else {
            $limit_ms = false;
        }


        list($test_result, $test_crono, $verdict) = @eval('
            $eval_crono = microtime(true); 
            $eval_result = '.$test_code.'; 
            $eval_crono = microtime(true) - $eval_crono;
            return [$eval_result, $eval_crono, ('.($operator?$test_expected.' '.$operator.' ':'').'$eval_result)?true:false];');


        test_print_html($test_expected, $test_line, '', $limit_ms, $pass_fail, $verdict, $test_result, $test_crono);
    }
}



function test($test_expected, $test_code, string $comment = '', $limit_ms = false, bool $pass_fail = false) {
	
    if (is_string($test_code) AND substr($test_code, -1, 1) === ';')
		$test_code = substr($test_code, 0, -1);

    if ($test_code === null)
        $test_code = 'null';


	list($test_result, $test_crono) = @eval('
        $eval_crono = microtime(true);
        $eval_result = '.$test_code.';
        $eval_crono = microtime(true) - $eval_crono;
        return [$eval_result, $eval_crono];');


	$verdict = false;

	if ($test_result === $test_expected)
		$verdict = true;

	else if (is_float($test_result) AND $test_result == $test_expected)
		$verdict = true;

	else if (is_array($test_expected) AND $test_result === $test_expected)
		$verdict = true;

	else if (is_string($test_expected) AND substr((string)$test_expected, 0, 1) == '/' AND @preg_match($test_expected, $test_result))
		$verdict = true;

	else if (is_string($test_expected) AND preg_match("/^(<|>|>=|<=)[0-9\.]{1,}$/", (string)$test_expected)) {
		$number = str_replace(array('>', '<', '='), '', $test_expected);

		if (substr($test_expected, 0, 1) == '>' AND is_numeric($test_result) AND $test_result >  $number)
			$verdict = true;
		else if (substr($test_expected, 0, 2) == '>=' AND is_numeric($test_result) AND $test_result >= $number)
			$verdict = true;
		else if (substr($test_expected, 0, 1) == '<' AND is_numeric($test_result) AND $test_result <  $number)
			$verdict = true;
		else if (substr($test_expected, 0, 2) == '<=' AND is_numeric($test_result) AND $test_result <= $number)
			$verdict = true;
	}


    return test_print_html($test_expected, $test_code, $comment, $limit_ms, $pass_fail, $verdict, $test_result, $test_crono, false);
}