<?php
require_once(__DIR__.'/Bootstrap.php');
class generateModels extends \York\Console\Application{

	function help()
	{
		$this->output('just run');
	}

	public function run()
	{
		$generator = new \York\Database\Model\Generator();
		$generator->generateAll();
	}
}

new generateModels('generate models', '1.0');


