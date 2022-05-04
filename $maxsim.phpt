<?

// Test execution in /maxsim/tests

# MAXSIM
isset($GLOBALS['maxsim'])
json_decode(test_url('/$maxsim'), true)['version']
true === is_readable('$maxsim.php')


# DIR
'exec.php' === basename($GLOBALS['maxsim']['app'])
'maxsim/tests/' === $GLOBALS['maxsim']['app_dir']
'/maxsim/tests/exec' === $GLOBALS['maxsim']['app_url']
'' === maxsim_dir()
'maxsim/tests/' === maxsim_dir(__DIR__)
true === in_array($GLOBALS['maxsim']['app_dir'].'+.php', $GLOBALS['maxsim']['autoload'])


# GET
'$maxsim.phpt' === basename($_GET['file'])
'exec' === $_GET[0]
null === $_GET[1]


# STATUS 200
200 === test_url('/$maxsim', 'status')
200 === test_url($GLOBALS['maxsim']['app_url'].'/+.css', 'status')
200 === test_url($GLOBALS['maxsim']['app_url'], 'status')
200 === test_url('/$maxsim', 'status')
200 === test_url('/$maxsim/a?b=c', 'status')


# STATUS 404
404 === test_url('/no_exist', 'status')
404 === test_url('/+', 'status')
404 === test_url('/%24', 'status')
404 === test_url('/+/', 'status')
404 === test_url('/+/a', 'status')


# STATUS 403
403 === test_url('/index.php', 'status')
403 === test_url('/index.php/a', 'status')
403 === test_url('/index.php?a=b', 'status')

403 === test_url('/+passwords.ini', 'status')
403 === test_url('/$maxsim.phpt', 'status')
403 === test_url('/maxsim/tests/tests.phpt', 'status')
403 === test_url('/+.php', 'status')

403 === test_url('/.git', 'status')
403 === test_url('/.git/index', 'status')
403 === test_url('/.git/logs/HEAD', 'status')

403 === test_url('/docker-compose.yml', 'status')
403 === test_url('/no_exist.phpt', 'status')
403 === test_url('/no_exist.json', 'status')
403 === test_url('/no_exist.xml', 'status')
403 === test_url('/no_exist.log', 'status')
403 === test_url('/.gitignore', 'status')
403 === test_url($GLOBALS['maxsim']['app_url'].'/.gitignore', 'status')
403 === test_url('/.htaccess', 'status')
403 === test_url('/.htpasswd', 'status')


# Test app file
$test_app = 'test_'.mt_rand(10000000,99999999)
404  === test_url('/'.$test_app, 'status')
file_put_contents($test_app.'.php', '<?php exit(\'ok\');')
200  === test_url('/'.$test_app, 'status')
'ok' === test_url('/'.$test_app)
'ok' === test_url('/'.$test_app.'?a=b')
'ok' === test_url('/'.$test_app.'/a?b=c')
'ok' === test_url('/'.$test_app.'/a/b?c=d')
'ok' === test_url('/'.$test_app.'/a/b/?c=d')
unlink($test_app.'.php')

# Test app dir
$test_app_dir = 'dir_'.mt_rand(10000000,99999999)
false === test_url('/'.$test_app_dir)
404  === test_url('/'.$test_app_dir, 'status')
mkdir($test_app_dir)
file_put_contents($test_app_dir.'/index.php', '<?php exit(\'ok\');')
200  === test_url('/'.$test_app_dir.'/a/b/?c=d', 'status')
200  === test_url('/'.$test_app_dir, 'status')
'ok' === test_url('/'.$test_app_dir)
'ok' === test_url('/'.$test_app_dir.'?a=b')
'ok' === test_url('/'.$test_app_dir.'/a?b=c')
'ok' === test_url('/'.$test_app_dir.'/a/b?c=d')
'ok' === test_url('/'.$test_app_dir.'/a/b/?c=d')
unlink($test_app_dir.'/index.php')
rmdir($test_app_dir)