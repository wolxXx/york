<?php
namespace York\Console;


abstract class Application {
	protected $argc;
	protected $argv;
	protected $scriptName;
	protected $version;
	protected $required = array();
	protected $optional = array();
	protected $alias = array();

	protected $isDebugEnabled = false;
	protected $isVerboseEnabled = false;


	public final function __construct($scriptName = null, $version = null){
		$this->clearScreen();
		$this
			->setScriptName($scriptName)
			->setVersion($version);
		try{
			$this->check();
		}catch (\York\Exception\Console $exception){
			$this->out('York Cli Setup Error: '.$exception->getMessage().' in class '.get_called_class());
			die();
		}
		$this->welcome();
		try{
			$this->parseArgs();
		} catch (\York\Exception\Console $exception){
			$this->help();
			die();
		}
		$this->beforeRun();
		$this->run();
		$this->afterRun();
	}

	protected function setScriptName($scriptName = null){
		$this->scriptName = $scriptName;
		return $this;
	}

	public function getScriptName(){
		return $this->scriptName;
	}

	protected function setVersion($version){
		$this->version = $version;
		return $this;
	}

	public function getVersion(){
		return $this->version;
	}

	private final function check(){
		if(false === isset($this->scriptName) || true === $this->scriptName){
			throw new \York\Exception\Console('ensure scriptname is set!');
		}

		if(false === isset($this->version) || true === $this->version){
			throw new \York\Exception\Console('ensure scriptname is set!');
		}

		if(false === \York\Helper\Application::isCli()){
			throw new \York\Exception\Console('tried to run cli script in non-cli environment!');
		}
		return $this;
	}

	protected function parseArgs(){
		$this->argc = $_SERVER['argc'];
		$this->argv = $_SERVER['argv'];
		return $this;
	}

	public function out(){
		foreach(func_get_args() as $current){
			echo $current.PHP_EOL;
		}
	}

	protected function clearScreen(){
		system('clear');
	}


	private final function welcome(){
		$message = <<<TEXT
__   __         _       ____ _     ___      _                _ _           _   _
\ \ / /__  _ __| | __  / ___| |   |_ _|    / \   _ __  _ __ | (_) ___ __ _| |_(_) ___  _ __
 \ V / _ \| '__| |/ / | |   | |    | |    / _ \ | '_ \| '_ \| | |/ __/ _` | __| |/ _ \| '_ \
  | | (_) | |  |   <  | |___| |___ | |   / ___ \| |_) | |_) | | | (_| (_| | |_| | (_) | | | |
  |_|\___/|_|  |_|\_\  \____|_____|___| /_/   \_\ .__/| .__/|_|_|\___\__,_|\__|_|\___/|_| |_|
                                                |_|   |_|
____________________________________
{$this->scriptName} - v. {$this->version}
____________________________________
TEXT;
		$this->out($message);
		usleep(1000000);
		return $this;
	}

	public function beforeRun(){
		//overwrite me!
	}

	private final function afterRun(){
		$this->out('');
		$this->out('');
		$this->out('.... York Cli done. bye bye');
		$this->out('');
		$this->out('');
	}

	public abstract function help();

	public abstract function run();
}
