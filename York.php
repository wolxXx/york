<?php
namespace York;
/**
 * initialises everything useful like config, auto-loading, etc
 * then run the bootstrap class methods beforeRun, run, afterRun
 * then call hooks beforeView and afterView, in between call the view loader
 * if anything bad happens and an exception is thrown and not caught by causer, it will be caught here
 *
 * @version 3.0
 * @author wolxXx
 * @package York
 */

class York{
	/**
	 * constructor
	 */
	public final function __construct(){
		$this->initAutoloader();
	}

	/**
	 * @return \Application\Configuration\Bootstrap
	 * @codeCoverageIgnore
	 */
	public function getBootstrap(){
		return new \Application\Configuration\Bootstrap();
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function initAutoloader(){
		require_once __DIR__.'/Autoload/Manager.php';
		new \York\Autoload\Manager();
	}

	/**
	 * initialises the autoloader
	 * runs all steps from bootstrap process:
	 * 	beforeRun, run, afterRun, beforeView, view, afterView
	 * catches exceptions:
	 * 	DBException, QueryGeneratorException, NoViewException, ApocalypseException, Exception
	 */
	public function run(){
		try{
			$bootstrap = $this->getBootstrap();
			$bootstrap->beforeRun();
			$bootstrap->run();
			$bootstrap->afterRun();
			$bootstrap->beforeView();
			$bootstrap->view();
			$bootstrap->afterView();
		}catch(\York\Exception\Redirect $exception){
			$redirect = $bootstrap->controller->getRegisteredRedirect();
			if(null === $redirect){
				$redirect = \Application\Configuration\Dependency::getApplicationConfiguration()->getSafely('requestedRedirect', null);
				if(null === $redirect){
					$redirect = new Redirect('/');
				}
			}
			$redirect->redirect();
		}catch(\York\Exception\QueryGenerator $exception){
			$message = sprintf('[%s] %s:%s %s', \York\Helper\Date::getDate(), $exception->getFile(), $exception->getLine(), $exception->getMessage());
			\York\Dependency\Manager::getLogger()->log($message, \York\Logger\Manager::LEVEL_DATABASE_ERROR);

			if(null !== $bootstrap){
				$bootstrap->databaseError($exception);
			}
		}catch(\York\Exception\Database $exception){
			$message = sprintf('[%s] %s:%s %s', \York\Helper\Date::getDate(), $exception->getFile(), $exception->getLine(), $exception->getMessage());
			\York\Dependency\Manager::getLogger()->log($message, \York\Logger\Manager::LEVEL_DATABASE_ERROR);

			if(null !== $bootstrap){
				$bootstrap->databaseError($exception);
			}
		}catch(\York\Exception\NoView $exception){
			$message = sprintf('[%s] %s:%s %s', \York\Helper\Date::getDate(), $exception->getFile(), $exception->getLine(), $exception->getMessage());
			\York\Dependency\Manager::getLogger()->log($message, \York\Logger\Manager::LEVEL_ERROR);

			$this->catchError('/error/noView');
			die();
		}catch(\York\Exception\Apocalypse $exception){
			$message = sprintf('[%s] %s:%s %s', \York\Helper\Date::getDate(), $exception->getFile(), $exception->getLine(), $exception->getMessage());
			\York\Dependency\Manager::getLogger()->log($message, \York\Logger\Manager::LEVEL_ERROR);

			if('production' !== \York\Dependency\Manager::getApplicationConfiguration()->getSafely('mode', 'production')){
				debug_print_backtrace();
			}




			die('apocalypse: '.$exception->getMessage());
			if('production' === Stack::getInstance()->get('mode')){
				\York\Helper::logerror($x->getMessage());
				die(\York\Translator::translate('Schlimmer Fehler.'));
			}
			throw $exception;
		}catch(\York\Exception\General $exception){
			#die('general: '.$exception->getMessage());
			self::displayError($exception->getCode(), $exception->getMessage(), $exception->getFile(), $exception->getLine(), $exception->getTrace());
			#\York\Helper::logerror('exeption in inital try catch: '.$exception->getMessage());
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
		\York\Dependency\Manager::getViewManager()->clearInstance();
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
			Typ: <?= $code.' '.\York\Helper\Application::errorCodeToString($code) ?><br />
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
	 * @throws \York\Exception\General
	 */
	public static function errorHandler($code, $message, $file, $line, $context = null){
		$text = <<<MESSAGE
an error occured:
{$message}
at line {$line} in file {$file}
MESSAGE;

		\York\Dependency\Manager::get('logger')->log($text, \York\Logger\Manager::LEVEL_DEBUG);
		\York\Dependency\Manager::get('logger')->log($text, \York\Logger\Manager::LEVEL_ERROR);
		$exception = new \York\Exception\General($message, $code);
		throw $exception;
	}
}
