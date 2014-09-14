<?
/**
 * @codeCoverageIgnore
 */
if('' !== session_id()){
	session_destroy();
}

require_once __DIR__.'/../Autoload/Manager.php';

chdir(__DIR__.'/../../');

new \York\Autoload\Manager();

\York\Configuration::$AUTH_CREDENTIAL_USER_ID = 'email';

require_once \York\Helper\Application::getApplicationRoot().'Configuration/Test.php';
