<?php # maxsim.tech — Copyright (c) 2020 Javier González González <gonzo@virtualpol.com> — MIT License


function e($danger_user_input) {
	return mysqli_real_escape_string(sql_link(), $danger_user_input);
}


function sql_connect($server_sql=false) {
    global $__sql;

    if (!$server_sql)
        $server_sql = getenv("DATABASE");

	$p = parse_url($server_sql);
	
	if (!$p['port'])
	    $p['port'] = 3306;

    $p['database'] = explode('/', $p['path'])[1];


	$sql_link = @mysqli_connect($p['host'], $p['user'], $p['pass'], $p['database'], $p['port']);
    
    $__sql['link'][] = $sql_link;
    
	if (!$sql_link)
		exit('<span title="'.sql_error().'">ERROR: Database connect error.</span>');

	//mysqli_query($sql_link, "SET NAMES 'utf8'");

    @register_shutdown_function('sql_close');

	return $sql_link;
}



function sql_link() {
	global $__sql;

	if (!$__sql['link'])
        sql_connect();

	return $__sql['link'][0];
}



function sql($query) {
    global $__sql;
	
    $result = mysqli_query(sql_link(), $query);
    
    $__sql['count']++;

    if ($result===true OR $result===false) 
        return $result;
    
    $output = [];
    while ($r = mysqli_fetch_array($result, MYSQLI_ASSOC))
        $output[] = $r;
    
	if (isset($output[0]['ECHO']))
		return $output[0]['ECHO'];
    
    return $output;
}



function sql_insert($table, $rows) {

	if (!is_array($rows))
		return false;

	if (!is_array($rows[0]))
		$rows = [$rows];

	if (count($rows)==0)
		return false;

	foreach ($rows AS $row_id => $row) {
		$columns_values = [];


		foreach ($row AS $key => $value) {

			if ($row_id==0)
				$columns[] = e($key);

			if ($value===null OR $value===false OR $value===true)
				$columns_values[] = 'NULL';
			else if (is_array($value))
				$columns_values[] = "'".e(json_encode($value, true))."'";
			else
				$columns_values[] = "'".e($value)."'";
		}

        if (count($columns_values)>0)
		    $values[] = "(".implode(",", $columns_values).")";

		if (++$values_num>=5000) {
			sql("INSERT INTO `".e($table)."` (".implode(',', (array)$columns).") VALUES ".implode(",", (array)$values));
			$values 	= [];
			$values_num = 0;
        }
	}

    if (count($values)>0)
        $res = sql("INSERT INTO `".e($table)."` (".implode(',', (array)$columns).") VALUES ".implode(",", (array)$values));

        
	if ($res===false)
		return false;
	else
		return mysqli_insert_id(sql_link());
}



function sql_update($table, $p, $w, $or_insert=false) {

	if (!is_array($p))
		return false;

	if ($or_insert===true) {
		$w = str_replace(' LIMIT 1', '', $w);

		if (sql("SELECT id FROM `".e($table)."` WHERE ".$w." LIMIT 1"))
            return sql_update($table, $p, $w);
		else
            return sql_insert($table, $p);
            
	} else {
		$a = [];
		foreach ($p AS $key => $value) {
			if ($value==='++')
				$a[] = "`".e($key)."` = `".e($key)."` + 1";
			else if ($value==='--')
				$a[] = "`".e($key)."` = `".e($key)."` - 1";
			else if ($value===null OR $value===true OR $value===false)
				$a[] = "`".e($key)."` = NULL";
			else if (is_array($value))
				$a[] = "`".e($key)."` = '".e(json_encode($value, true))."'";
			else
				$a[] = "`".e($key)."` = '".e($value)."'";
		}
		return sql("UPDATE `".e($table)."` SET ".implode(',', $a)." WHERE ".$w);
	}
}



function sql_primary_key($table) {
	
	$a = sql("SHOW KEYS FROM ".e($table)." WHERE Key_name = 'PRIMARY'");
	
	return ($a['Column_name']?$a['Column_name']:false);
}



function sql_get_tables() {
	$tables = [];
	foreach (sql("SHOW TABLES") AS $r)
		$tables[] = current($r);

	return $tables;
}



function sql_where($array=false, $operator='AND') {

	if (!is_array($array))
		return $array;

	if (count($array)==0)
		return '1';

	$element = [];
	foreach ((array)$array AS $item) {
		if ($item[1]=='IN') {
			foreach ((array)$item[2] AS $key=>$value)
				$item[2][$key] = '\''.e($value).'\'';
			$element[] = e($item[0])." IN (".implode(',', $item[2]).")";
		} else {
			$element[] = e($item[0])." ".e($item[1])." '".e($item[2])."'";
		}
	}

	return implode(' '.$operator.' ', $element);
}



function sql_key_value($key, $value=false) {

	if ($value===false)
		return sql("SELECT value AS ECHO FROM key_value WHERE name = '".e($key)."' LIMIT 1");
    
    sql_update('key_value', ['name' => $key, 'value' => $value], "name = '".e($key)."'", true);
	return $value;
}



function sql_lock($tables=false) {

    if (!is_array($tables))
        $tables = sql_get_tables();

    foreach ($tables AS $table)
        $elm[] = $table.' WRITE';

    sql("LOCK TABLES ".implode(', ', $elm));

    @register_shutdown_function('sql_unlock');
}



function sql_unlock() {
    sql("UNLOCK TABLES");
}



function sql_close() {
	global $__sql;

	foreach ((array)$__sql['link'] AS $link)
        mysqli_close($link);

    unset($__sql['link']);
}



function sql_error() {
	$msg = @mysqli_error(sql_link());
	return ($msg?$msg:'');
}
