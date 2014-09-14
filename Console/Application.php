<?php
namespace York\Console;
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
	 * flag for colors enabled
	 *
	 * @var boolean
	 */
	protected $isColorEnabled = true;

	/**
	 * default amount of microseconds to wait for next char
	 *
	 * @var integer
	 */
	protected $defaultTypingDelay = 5000;

	/**
	 * current amount of microseconds to wait for next char
	 *
	 * @var integer
	 */
	protected $typingDelay;

	/**
	 * default amount of microseconds to wait for carriage return
	 *
	 * @var integer
	 */
	protected $defaultCarriageReturnDelay = 200000;

	/**
	 * current amount of microseconds to wait for carriage return
	 *
	 * @var integer
	 */
	protected $carriageReturnDelay;

	/**
	 * amount of microseconds displaying the welcome screen
	 *
	 * @var integer
	 */
	protected $welcomeDelay = 210000;

	/**
	 * default string that will be displayed before output
	 *
	 * @var string
	 */
	protected $defaultOutputPrefix = '   ';

	/**
	 * string that will be displayed before output
	 *
	 * @var string
	 */
	protected $outputPrefix = '   ';

	/**
	 * start up
	 *
	 * @param string $scriptName
	 * @param string $version
	 * @throws \York\Exception\Console
	 */
	public final function __construct($scriptName, $version){
		if(false === \York\Helper\Application::isCli()){
			throw new Console('tried to run cli script in non-cli environment!');
		}
		$writer = \York\Writer\Console::Factory();
		\York\Dependency\Manager::setDependency('writer', $writer);

		$this->scriptName = $scriptName;
		$this->version = $version;
		$this->carriageReturnDelay = $this->defaultCarriageReturnDelay;
		$this->typingDelay = $this->defaultTypingDelay;

		#$this->init();
	}

	/**
	 * run the application
	 *
	 */
	public final function init(){
		$this->checkDefaultFlags();

		if(true == Flag::Factory('help', 'h')->isEnabled()){
			$this->debugOutput('help flag found. help enabled.');
			$this->helpWrap();
			return;
		}

		if(true == Flag::Factory('version', 'V')->isEnabled()){
			$this->debugOutput('version flag found. displaying help.');
			$this->showVersion();
			return;
		}

		$this->clearScreen();
		$this->welcome();
		$this->debugOutput('calling before run');
		$this->beforeRun();
		$this->debugOutput('calling run');

		$this->run();
		$this->debugOutput('calling after run');
		$this->afterRun();
		$this->quit();
	}

	/**
	 * checks the default flags
	 * quiet, debug, verbose
	 */
	protected function checkDefaultFlags(){
		$this->isQuietEnabled = true === Flag::Factory('quiet', 'q')->isEnabled();
		$this->isColorEnabled = false === Flag::Factory('no-colors')->isEnabled() && false ===  Flag::Factory('no-color')->isEnabled() ;
		$this->isDebugEnabled = true === Flag::Factory('debug', 'd')->isEnabled();
		$this->isVerboseEnabled = true === Flag::Factory('verbose', 'v')->isEnabled();
		if(true === Flag::Factory('fast')->isEnabled()){
			$this->defaultTypingDelay = 0;
			$this->typingDelay = 0;
			$this->defaultCarriageReturnDelay = 0;
			$this->carriageReturnDelay = 0;
		}
	}

	/**
	 * displays the version of this script
	 */
	protected function showVersion(){
		$this
			->clearScreen()
			->welcome()
			->output('Script: '.$this->colorString($this->scriptName, Style::FOREGROUND_YELLOW))
			->output('Version: '.$this->colorString($this->version, Style::FOREGROUND_YELLOW));
	}

	/**
	 * wrapper for help function
	 */
	protected final function helpWrap(){
		$this->isQuietEnabled = false;

		$this
			->clearScreen()
			->welcome();

		$this->help();
		$this
			->defaultHelp()
			->quit();
	}

	/**
	 * @return $this
	 */
	protected function defaultHelp(){
		return $this
			->newLine(2)
			->output('default and general and overall flag options:')
			->output('-v | --verbose: much more output')
			->output('-d | --debug: enable debug output')
			->output('-q | --quiet: no output')
			->output('-h | --help: display help')
			->output('-V | --version: display the version')
			->output('--no-colors: disable colored output')
			->output('--fast: disable delayed / typing output')
		;
	}

	/**
	 * output the given strings if the debug mode is enabled
	 *
	 * @param string[] $args
	 * @return $this
	 */
	public function debugOutput($args = array()){
		if(false === $this->isDebugEnabled){
			return $this;
		}

		foreach(func_get_args() as $current){
			$current =  '[DeBuG]: '.$current;
			if(true === $this->isColorEnabled){
				$current = Style::styleString($current, Style::FOREGROUND_PURPLE);
			}
			$this->output($current);
		}

		return $this;
	}

	/**
	 * @param array $args
	 * @return $this
	 */
	public function warningOutput($args = array()){
		foreach(func_get_args() as $current){
			$current =  '[warning]: '.$current;
			if(true === $this->isColorEnabled){
				$current = Style::styleString($current, Style::FOREGROUND_YELLOW);
			}
			$this->output($current);
		}

		return $this;
	}

	/**
	 * @param array $args
	 * @return $this
	 */
	public function successOutput($args = array()){
		foreach(func_get_args() as $current){
			$current =  '[OK]: '.$current;
			if(true === $this->isColorEnabled){
				$current = Style::styleString($current, Style::FOREGROUND_GREEN);
			}
			$this->output($current);
		}

		return $this;
	}

	/**
	 * output the given strings if the verbose mode is enabled
	 *
	 * @param string[] $args
	 * @return $this
	 */
	public function verboseOutput($args = array()){
		if(false === $this->isVerboseEnabled && false === $this->isDebugEnabled){
			return $this;
		}

		foreach(func_get_args() as $current){
			$this->output($current);
		}

		return $this;
	}

	/**
	 * display a red colored error string
	 *
	 * @param string[] $args
	 * @return $this
	 */
	public function errorOutput($args = array()){
		foreach(func_get_args() as $current){
			$current =  '[ERROR]: '.$current;
			if(true === $this->isColorEnabled){
				$current = Style::styleString($current, Style::FOREGROUND_RED);
			}
			$this->output($current);
		}

		return $this;
	}

	/**
	 * display a new line $amount times
	 *
	 * @param integer $amount
	 * @return $this
	 */
	public function newLine($amount = 1){
		foreach(range(0, $amount) as $counter){
			$this->outputPrefix = '';
			$this->output('');
			$this->outputPrefix = $this->defaultOutputPrefix;
		}

		return $this;
	}

	/**
	 * output all given strings if quiet mode is not enabled
	 *
	 * @param string[] $args
	 * @return $this
	 */
	public function output($args = array()){
		if(true === $this->isQuietEnabled){
			return $this;
		}

		foreach(func_get_args() as $current){
			foreach(str_split($this->outputPrefix.$current.PHP_EOL) as $char){
				\York\Dependency\Manager::get('writer')->write($char);
				usleep($this->typingDelay);
			}
			usleep($this->carriageReturnDelay);
		}

		return $this;
	}



	/**
	 * clear the screen
	 *
	 * @return $this
	 */
	protected function clearScreen(){
		if(true === $this->isQuietEnabled){
			return $this;
		}

		system('clear');

		return $this;
	}

	/**
	 * color the string if colors are enabled
	 *
	 * @param string $string
	 * @param string $foreGround
	 * @return string
	 */
	public function colorString($string, $foreGround = Style::FOREGROUND_RED){
		if(true === $this->isColorEnabled){
			$string = Style::styleString($string, $foreGround);
		}

		return $string;
	}

	/**
	 * prints the headline
	 *
	 * @return $this
	 */
	private final function welcome(){
		if(true === $this->isQuietEnabled){
			return $this;
		}

		$nameAndVersion = $this->scriptName.' v'.$this->version;

		if(true === $this->isColorEnabled){
			$nameAndVersion = Style::styleString($nameAndVersion, Style::FOREGROUND_YELLOW);
		}

		$message = \York\Template\Parser::parseFile(__DIR__.'/header', array(
			'nameAndVersion' => $nameAndVersion
		));

		$this->typingDelay = 0;
		$this->carriageReturnDelay = 0;

		if(false === $this->isColorEnabled){
			$this->output($message);
		}else{
			$messageRows = explode(PHP_EOL, $message);

			$rainbow = array(
				Style::FOREGROUND_RED,
				Style::FOREGROUND_PURPLE,
				Style::FOREGROUND_BLUE,
				Style::FOREGROUND_CYAN,
				Style::FOREGROUND_GREEN,
				Style::FOREGROUND_YELLOW,
				Style::FOREGROUND_BROWN
			);

			$rainbow = \York\Helper\Set::array_repeat($rainbow, ceil(sizeof($messageRows) / sizeof($rainbow)));

			foreach(explode(PHP_EOL, $message) as $index => $row){
				$this->output(Style::styleString($row, $rainbow[$index]));
			}
		}

		usleep($this->welcomeDelay);
		$this->carriageReturnDelay = $this->defaultCarriageReturnDelay;
		$this->typingDelay = $this->defaultTypingDelay;

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
	 * dire au revoir
	 */
	private function quit(){
		$this
			->newLine(2)
			->output('....done.')
			->newLine()
		;
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
