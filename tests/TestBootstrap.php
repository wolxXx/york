<?
if('' !== session_id()){
	session_destroy();
}
#if(true === file_exists(__DIR__.'/../../../application/config/defines.php')){
#	require_once __DIR__.'/../../../application/config/defines.php';
#}

require_once __DIR__.'/../Autoload/Manager.php';

#chdir(__DIR__.'/../../');

//ini_alter('xdebug.var_display_max_data', '5');
//ini_alter('xdebug.var_display_max_children', '5');
//ini_alter('xdebug.var_display_max_depth', '5');

new \York\Autoload\Manager();

\York\Configuration::$AUTH_CREDENTIAL_USER_ID = 'email';

\York\Dependency\Manager::get('applicationConfiguration')
	->set('db_host', null)
	->set('db_user', null)
	->set('db_pass', null)
	->set('db_schema', null);
