<?php
namespace York;
use Application\Configuration\Bootstrap;
use York\Autoload\Manager;
use York\Exception\Database;

/**
 * initialises everything usefull like config, auto-loading, etc
 * then run the bootstrap class methos beforeRun, run, afterRun
 * then call hooks beforeView and afterView, in between call the view loader
 * if anything bad happens and an exception is thrown and not catched by causer, it will be catched here
 *
 * @version 3.0
 * @author wolxXx
 * @package York
 */

final class York{
	/**
	 * constructor
	 */
	public function __construct(){
		$this->run();
	}

	/**
	 * initliaises the autoloader
	 * runs all steps from bootstrap process:
	 * 	beforeRun, run, afterRun, beforeView, view, afterView
	 * catches exceptions:
	 * 	DBException, QueryGeneratorException, NoViewException, ApocalypseException, Exception
	 */
	public function run(){
		try{
			require_once __DIR__.'/Autoload/Manager.php';
			new Manager();
			$bootstrap = new Bootstrap();
			$bootstrap->beforeRun();
			$bootstrap->run();
			$bootstrap->afterRun();
			$bootstrap->beforeView();
			$bootstrap->view();
			$bootstrap->afterView();
		}catch(Database $exception){
			die('database: '.$exception->getMessage());
			\York\Helper::logerror('got no database connection: '.$exception->getMessage());
			require_once 'tot.html';
		}catch(\York\Exception\QueryGenerator $exception){
			die('query: '.$exception->getMessage());
			\York\Helper::logToFile('the query generator failed!! '.$exception->getMessage(), 'dberror');
			require_once 'tot.html';
		}catch(\York\Exception\NoView $exception){
			\York\Helper\Application::debug($exception);
			die('no view: '.$exception->getMessage());
			$this->catchError('/error/noView');
			die('');
		}catch(\York\Exception\Apocalypse $exception){
			var_dump(debug_backtrace());
			die('apocalypse: '.$exception->getMessage());
			if('production' === Stack::getInstance()->get('mode')){
				\York\Helper::logerror($x->getMessage());
				die(\York\Translator::translate('Schlimmer Fehler.'));
			}
			throw $exception;
		}catch(\York $exception){
			die('general: '.$exception->getMessage());
			if(true === \York\Helper::isDebugEnabled()){
				self::displayError($exception->getCode(), $exception->getMessage(), $exception->getFile(), $exception->getLine(), $exception->getTrace());
			}
			\York\Helper::logerror('exeption in inital try catch: '.$exception->getMessage());
			$this->catchError('/error/app');
			return;
		}
	}

	/**
	 * catches an error and tries to generate the error views
	 *
	 * @param string $newURI
	 */
	public function catchError($newURI = '/error/app'){
		$_SERVER['REQUEST_URI'] = $newURI;
		\York\View\Manager::clearInstance();
		try{
			$this->run();
		}catch(\Exception $exception){
			die('too many errors oO');
		}
	}

	/**
	 * displays the error and dies
	 *
	 * @param integer $code
	 * @param string $message
	 * @param string $file
	 * @param integer $line
	 * @param mixed $context
	 */
	public static function displayError($code, $message, $file, $line, $context = null){
		?>
			<h1>oO an error occured</h1>
			<h2><?= $message ?></h2>
			Typ: <?= $code.' '.\York\Helper::errorCodeToString($code) ?><br />
			<?= $file ?> : <?= $line ?>
		<?php
		foreach($context as $row){
			var_dump($row);
		}
		die();
	}

	/**
	 * catches the exception that is thrown in error handler
	 * @throws Exception
	 */
	public static function catchException(){
		$exception = func_get_arg(0);
		if(true === \York\Helper::isDebugEnabled()){
			self::displayError($exception->getCode(), $exception->getMessage(), $exception->getFile(), $exception->getLine(), $exception->getTrace());
		}
		\York\Helper::logerror($exception->getMessage());

		die();
	}

	/**
	 * dedicated error handler
	 *
	 * @param integer $code
	 * @param string $message
	 * @param string $file
	 * @param integer $line
	 * @param string $context
	 * @throws \York\Exception\York
	 */
	public static function errorHandler($code, $message, $file, $line, $context = null){
		$text = <<<MESSAGE
an error occured:
{$message}
at line {$line} in file {$file}
MESSAGE;

		\York\Dependency\Manager::get('logger')->log($text, \York\Logger\Manager::LEVEL_DEBUG);
		\York\Dependency\Manager::get('logger')->log($text, \York\Logger\Manager::LEVEL_ERROR);
		$exception = new \York\Exception\York($message, $code);
		throw $exception;
	}
}

/**
 * now... let the hammer fall!
 */
new York();
