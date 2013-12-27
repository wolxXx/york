<?
if('' !== session_id()){
	session_destroy();
}
#if(true === file_exists(__DIR__.'/../../../application/config/defines.php')){
#	require_once __DIR__.'/../../../application/config/defines.php';
#}

require_once __DIR__.'/../Autoload/Manager.php';

chdir(__DIR__.'/../../');

//ini_alter('xdebug.var_display_max_data', '5');
//ini_alter('xdebug.var_display_max_children', '5');
//ini_alter('xdebug.var_display_max_depth', '5');

new \York\Autoload\Manager();