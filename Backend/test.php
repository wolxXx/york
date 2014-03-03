<?php
require_once(__DIR__.'/Bootstrap.php');

/*\York\Helper\Application::debug(getopt('qvd', array(
	'model::',
	'debug',
	'verbose',
	'quiet'
)));

\York\Helper\Application::debug($argv);

\York\Helper\Application::dieDebug('fuck you!');


# php script.php --verbose --debug --quiet -q
*/
class foobar extends \York\Console\Application{
	public $model;

	/***
	 * must be implemented by extending class
	 * shall display usage and help text
	 */
	public function help()
	{
		$this->output('foobar');
	}

	/**
	 * must be implemented by extending class
	 * drop your business logic here....
	 */
	public function run()
	{

	}

	public function initParameters()
	{

	}
}

new foobar('foobar', '1');
