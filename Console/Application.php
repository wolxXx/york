<?php
namespace York\Console;
use York\Exception\Console;

/**
 * abstract class for console applications
 * parameters / arguments must be passed via "--key value"
 *
 * @author wolxXx
 * @version 3.0
 * @package York\Console
 */
abstract class Application {
	/**
	 * original argument counter
	 *
	 * @var integer
	 */
	protected $argc;

	/**
	 * original argument values
	 *
	 * @var array
	 */
	protected $argv;

	/**
	 * the script name
	 *
	 * @var string
	 */
	protected $scriptName;

	/**
	 * the script version
	 *
	 * @var string
	 */
	protected $version;

	/**
	 * required arguments
	 *
	 * @var array
	 */
	protected $required = array();

	/**
	 * optional arguments
	 *
	 * @var array
	 */
	protected $optional = array();

	/**
	 * given parameters
	 *
	 * @var array
	 */
	protected $parameters = array();

	/**
	 * @var Parameter[]
	 */
	protected $params = array();

	/**
	 * flag for debug enabled
	 *
	 * @var boolean
	 */
	protected $isDebugEnabled = false;

	/**
	 * flag for verbose output enabled
	 *
	 * @var boolean
	 */
	protected $isVerboseEnabled = false;

	/**
	 * flag for quiet mode enabled
	 *
	 * @var boolean
	 */
	protected $isQuietEnabled = false;

	/**
	 * start up
	 *
	 * @param string $scriptName
	 * @param string $version
	 */
	public final function __construct($scriptName, $version){
		$this->init($scriptName, $version);
	}

	/**
	 * run the application
	 *
	 * @param $scriptName
	 * @param $version
	 */
	protected function init($scriptName, $version){
		$this->clearScreen();
		$this
			->setScriptName($scriptName)
			->setVersion($version);
		try{
			$this->check();
		}catch (Console $exception){
			$this->output('York Cli Setup Error: '.$exception->getMessage().' in class '.get_called_class());
			die();
		}
		$this->welcome();
		try{
			$this->parseArgs();
		} catch (Console $exception){
			$this->help($exception->getMessage());
			die();
		}
		$this->beforeRun();
		$this->run();
		$this->afterRun();
		$this->quit();
	}

	/**
	 * setter for the script name
	 *
	 * @param string $scriptName
	 * @return Application
	 */
	protected function setScriptName($scriptName){
		$this->scriptName = $scriptName;
		return $this;
	}

	/**
	 * getter for the script name
	 *
	 * @return string
	 */
	public function getScriptName(){
		return $this->scriptName;
	}

	/**
	 * setter for the version
	 *
	 * @param string $version
	 * @return Application
	 */
	protected function setVersion($version){
		$this->version = $version;
		return $this;
	}

	/**
	 * getter for the version
	 *
	 * @return string
	 */
	public function getVersion(){
		return $this->version;
	}

	/**
	 * checks the given parameters
	 *
	 * @return Application
	 * @throws \York\Exception\Console
	 */
	private final function check(){
		if(false === isset($this->scriptName) || true === $this->scriptName){
			throw new Console('ensure scriptname is set!');
		}

		if(false === isset($this->version) || true === $this->version){
			throw new Console('ensure scriptname is set!');
		}

		if(false === \York\Helper\Application::isCli()){
			throw new Console('tried to run cli script in non-cli environment!');
		}
		return $this;
	}

	/**
	 * @return array
	 */
	protected function getParameters(){
		return $this->parameters;
	}

	/**
	 * getter for a parameter
	 *
	 * @param string $key
	 * @param mixed $default
	 * @return mixed
	 */
	protected function getParameter($key, $default = null){
		if(false === array_key_exists($key, $this->parameters)){
			return $default;
		}

		return $this->parameters[$key];
	}

	/**
	 * parse the given arguments
	 *
	 * @return Application
	 * @throws \York\Exception\Console
	 */
	protected function parseArgs(){
		$this->argc = $_SERVER['argc'];
		$this->argv = $_SERVER['argv'];
		if(1 !== $this->argc % 2){
			throw new Console('can not parse arguments. make sure that --$key $value is correct!');
		}

		for($counter = 1; $counter < sizeof($this->argv); $counter++){
			$this->parameters[substr($this->argv[$counter], 2, strlen($this->argv[$counter]))] = $this->argv[++$counter];
		}

		return $this;
	}

	/**
	 * output the given strings if the debug mode is enabled
	 *
	 * @param string[] $args
	 * @return Application
	 */
	public function debugOutput($args = array()){
		if(false === $this->isDebugEnabled){
			return $this;
		}

		foreach(func_get_args() as $current){
			$this->output($current);
		}

		return $this;
	}

	/**
	 * output the given strings if the verbose mode is enabled
	 *
	 * @param string[] $args
	 * @return Application
	 */
	public function verboseOutput($args = array()){
		if(false === $this->isVerboseEnabled){
			return $this;
		}

		foreach(func_get_args() as $current){
			$this->output($current);
		}

		return $this;
	}

	/**
	 * output all given strings if quiet mode is not enabled
	 *
	 * @param string[] $args
	 * @return Application
	 */
	public function output($args = array()){
		if(true === $this->isQuietEnabled){
			return $this;
		}

		foreach(func_get_args() as $current){
			echo $current.PHP_EOL;
		}

		return $this;
	}

	/**
	 * clear the screen
	 *
	 * @return Application
	 */
	protected function clearScreen(){
		system('clear');

		return $this;
	}

	/**
	 * prints the headline
	 *
	 * @return Application
	 */
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
		$this->output($message);

		return $this;
	}

	/**
	 * can be overwritten if needed
	 * is ran before the run call
	 */
	public function beforeRun(){
		//overwrite me!
	}

	/**
	 * can be overwritten if needed
	 * is ran after the run call
	 */
	public function afterRun(){
		//overwrite me!
	}

	/**
	 * dir au revoir
	 */
	private final function quit(){
		$this->output('');
		$this->output('');
		$this->output('.... York Cli done. bye bye');
		$this->output('');
		$this->output('');
	}

	/***
	 * must be implemented by extending class
	 * shall display usage and help text
	 */
	public abstract function help();

	/**
	 * must be implemented by extending class
	 * drop your business logic here....
	 */
	public abstract function run();
}
