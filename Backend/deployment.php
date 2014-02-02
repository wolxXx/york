<?php
require_once(__DIR__.'/Bootstrap.php');
class Deployment extends \York\Console\Application{

	public function help()
	{
		$this->out(sprintf('call: %s pathToApplication mode', $this->argv[0]));
	}

	public function run()
	{
		$this->out('lol!');
	}
}

new Deployment('York Deployment', '1.0');
