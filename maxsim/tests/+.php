<?php # maxsim.tech — MIT License — Copyright (c) 2005 Javier González González <gonzo@virtualpol.com>


ini_set("highlight.comment", "#008000");
ini_set("highlight.default", "#000000");
ini_set("highlight.html", "#808080");
ini_set("highlight.keyword", "#0000BB; font-weight: bold");
ini_set("highlight.string", "#b10000");


function test_print_html($expected, $code, $comment = '', $limit_ms = false, 
    $pass_fail = false, $verdict = false, $result = false, $crono = false, bool $v2 = true) {

    global $unit_test;


    $limit_ms_fail = false;
	if (is_numeric($limit_ms) AND $limit_ms >= 1 AND ($crono * 1000) > $limit_ms) {
		$verdict = false;
        $limit_ms_fail = true;
    }


	if ($pass_fail === true) {
		if ($verdict === true)
			$verdict = false;
		else if ($verdict === false)
			$verdict = true;
	}

	if ($verdict === true)
		$unit_test['tests_pass'] = ($unit_test['tests_pass'] ?? 0) + 1;

        

	$unit_test['last_test_result'] = $result;

    if ($verdict === true AND $pass_fail === true)
        $verdict_text = '<b style="color:blue;">PASS FAIL</b>';
    else if ($verdict === true)
        $verdict_text = '<b style="color:blue;">PASS</b>';
    else
        $verdict_text = '<b style="color:red;">FAIL</b>'; 


    

    $print = 'table';

	if ($print == 'text')
		echo num($crono * 1000, 2) . ' ms ' . $verdict_text . ' <em><b>' . print_r($result, false) . '</b></em> = ' . $code . '<br />';


	if ($print == 'table') {
		$unit_test['tests_total'] = ($unit_test['tests_total'] ?? 0) + 1;
        echo '<tr style="font-family:monospace;">
				<td align="right"><b>'.$unit_test['tests_total'].'.</b></td>
				<td nowrap align="right"' . (is_numeric($limit_ms) ? ' title="Limit: ' . $limit_ms . ' ms"' : '') . ' style="' . (is_numeric($limit_ms) ? 'font-weight:bold;' . ($limit_ms_fail === true? 'color:red;' : 'color:blue;') : 'color:#555;') . '">' . num($crono * 1000, 2) . ' ms</td>
				<td nowrap>' . $verdict_text . '</td>';

        if ($v2 === true)
            echo '
                <td align="right" nowrap style="overflow-x:auto;max-width:400px;">
                    '.print_var($result).'
                </td>
                <td></td>
                <td></td>';
        
        else
            echo '
                <td align="right" nowrap>' . print_var($expected) . '</td>
                <td align="right">'.($verdict !== $pass_fail ? '===' : '!==').'</td>
                <td nowrap style="overflow-x:auto;max-width:400px;">'.print_var($result).'</td>';

                // max-width:600px;
        echo '  <td width="100%" style="background:#EEE;padding-left:10px;"><div style="word-wrap:break-word;">'.str_replace('\\n', '<br />', str_replace('">&lt;?php&nbsp;', '">', highlight_string('<?php '.$code, true))).'</div></td>
				<td>' . $comment . '</td>
			</tr>';
    }

	$unit_test['tests_crono'] = ($unit_test['tests_crono'] ?? 0) + $crono;

	ob_flush();

	return $verdict;
}



function print_var($var) {

	if ($var === true)
		return '<b>true</b>';

	if ($var === false)
		return '<b>false</b>';

	if ($var === null)
		return '<b>null</b>';

	if (is_string($var) AND preg_match("/^(<|>|>=|<=)[0-9\.]{1,}$/", $var))
		return '<b>' . $var . '</b>';

	if (is_int($var) or is_float($var))
		return '<b>' . $var . '</b>';

	if (is_array($var) or is_object($var))
		return '<xmp style="background:#EEE;text-align:left;padding-right:8px;">' . print_r($var, true) . '</xmp>';

	if (substr($var, 0, 1) == '/' AND substr($var, -1, 1) == '/')
		return '<span style="color:#999;">' . $var . '</span>';

	if (is_string($var))
		return '<b>\'</b><xmp style="display:inline;">'.$var.'</xmp><b>\'</b>';

	return $var;
}



function test_tr($text = '', $h = 4) {
	echo '<tr><td colspan="8"><br /><span style="font-size:16px;color:green;font-weight:bold;">'.$text.'</span></td></tr>';
}



function test_print($text) {
	echo '</table>' . print_r2($text, true) . '<table>';
}


function test_url(string $url, string $mode = 'html') {

    if (strpos($url, '://') === false) {
        if (substr($url, 0, 1) !== '/')
            return false;

        if ($_SERVER['SERVER_ADDR'] === $_SERVER['SERVER_NAME'])
            $host = 'localhost';
        else
            $host = $_SERVER['HTTP_HOST'];

        $url = $_SERVER['REQUEST_SCHEME'].'://'.$host.$url;
    }

    $html = file_get_contents($url);
    
    if ($mode === 'status')
        return (int) trim(explode(' ', $http_response_header[0])[1]);
    else
        return $html;
}
