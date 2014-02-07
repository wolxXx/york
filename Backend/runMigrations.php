<?php
require_once(__DIR__.'/Bootstrap.php');
class runMigrations extends \York\Console\Application{

	public function help(){
		$this->output('foobar');
	}

	public function run(){
		$this->verboseOutput('lorl');
		$this->debugOutput('lorl2');
		$this->output('foobar');
	}
}

new runMigrations('run migrations', '0.1');
