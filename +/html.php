<?php


function html_table($data, $config=false) {
    
    if (!is_array($data))
        return '';


    // Header
    if ($config['tr_th_extra'])
        $html .= $config['tr_th_extra'];


    $html .= '<tr style="'.($config['th_background_color']?'background-color:'.$config['th_background_color'].';':'').'">';
    foreach ((array)$data[key($data)] AS $key => $value) {
        $th_extra = '';

        if ($config[$key]['background_color'])
            $th_extra .= ' style="background-color:'.$config[$key]['background_color'].';"';

        if (isset($config[$key]['th']))
            $key = $config[$key]['th'];

        $html .= '<th'.$th_extra.'>'.ucfirst($key).'</th>';
    }
    $html .= '</tr>'."\n";
    
    
    // Rows
    foreach ($data AS $row) {
        $tr_extra = '';
        $td = '';
        foreach ($row AS $key => $column) {
            $td_extra = '';
            
            if (is_array($column))
                $column = implode(', ', $column);
                
            if ($config[$key]['align'])
                $td_extra .= ' align="'.$config[$key]['align'].'"';

            if ($config[$key]['monospace'])
                $td_extra .= ' class="monospace"';

            if ($config[$key]['background_color'])
                $td_extra .= ' style="background-color:'.$config[$key]['background_color'].';"';

            if ($config[$key]['height'])
                $td_extra .= ' style="height:'.$config[$key]['height'].'px;"';

            if ($config[$key]['tr_background_color'])
                if ($config[$key]['tr_background_color'][$column])
                    $tr_extra .= ' style="background-color:'.$config[$key]['tr_background_color'][$column].';"';

            if ($config[$key]['function'])
                $column = call_user_func($config[$key]['function'], $column);

            if (is_numeric($config[$key]['num']))
                $column = num($column,$config[$key]['num']);

            if ($config[$key]['ucfirst'])
                $column = ucfirst($column);

            if ($config[$key]['capital'])
                $column = strtoupper($column);

            if ($config[$key]['before'])
                $column = $config[$key]['before'].$column;

            if ($config[$key]['after'])
                $column = $column.$config[$key]['after'];

            if ($config[$key]['bold'])
                $column = '<b>'.$column.'</b>';

            $td .= '<td'.$td_extra.' nowrap>'.$column.'</td>';
        }
        $html .= '<tr'.$tr_extra.'>'.$td.'</tr>'."\n";
    }
    

    return "\n\n".'<table>'.$html.'</table>'."\n\n";
}


function html_a($url, $text, $blank=false) {
    return '<a href="'.$url.'"'.($blank?' target="_blank"':'').'>'.$text.'</a>';
}


function html_b($text) {
    return '<b>'.$text.'</b>';
}


function html_h($text, $num=1) {
    return '<h'.$num.'>'.$text.'</h'.$num.'>';
}


function html_button($url=false, $text='', $style='primary', $extra=false) {
    if ($url)
        return '<a href="'.$url.'" class="btn btn-'.$style.'">'.$text.'</a>';
    else
        return '<button type="button" class="btn btn-'.$style.'">'.$text.'</button>';
}