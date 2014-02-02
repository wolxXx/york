<?php
namespace York\Helper;
use York\Dependency\Manager as Dependency;

/**
 * application helper utilities class
 *
 * @author wolxXx
 * @version 3.0
 * @package York\Helper
 */
class Application{
	/**
	 * checks if the application runs in cli mode
	 *
	 * @return boolean
	 */
	public static function isCli(){
		return false !== isset($_SERVER['argv']);
	}

	/**
	 * debugs all given params and dies
	 */
	public static function dieDebug(){
		foreach(func_get_args() as $current){
			self::debug($current);
		}
		die('die debug called. stopping here...'.PHP_EOL);
	}

	/**
	 * debugs all given params
	 */
	public static function debug(){
		$backtrace = debug_backtrace(true);
		$trace = $backtrace[0];

		if(__FILE__ === $trace['file']){
			$trace = $backtrace[1];
		}
		$line = isset($trace['line'])? $trace['line'] : 666;
		$file = isset($trace['file'])? $trace['file'] : 'somewhere';
		if(false === self::isCli()){
			echo '<div class="debug"><pre>debug from '.(str_replace(self::getDocRoot(), '', $file)).' line '.$line.':</pre>';
		}else{
			echo 'debug from '.(str_replace(self::getDocRoot(), '', $file)).' line '.$line.':'.PHP_EOL;
		}

		foreach(func_get_args() as $arg){
			var_dump($arg);
		}
		if(false === self::isCli()){
			echo '</div>';
		}else{
			echo '____________________'.PHP_EOL.PHP_EOL;
		}

	}

	/**
	 * provides the path of the docroot with tailing slash
	 *
	 * @return string
	 */
	public static function getDocRoot(){
		return realpath(__DIR__.'/../../').'/';
	}

	/**
	 * retrieves the application root
	 *
	 * @return string
	 */
	public static function getApplicationRoot(){
		$path = __DIR__.'/../../../Application';
		$path = realpath($path).DIRECTORY_SEPARATOR;
		return $path;
	}

	/**
	 * redirects to $url or reloads the site if $url is null
	 *
	 * @param string $url
	 */
	public static function redirect($url = null){
		if(null === $url){
			$url = '/';
			if(true === isset($_SERVER['REQUEST_URI'])){
				$url = $_SERVER['REQUEST_URI'];
			}
		}
		header('Location:'.$url);
		die();
	}

	/**
	 * redirects with telling the browser that the target moved
	 *
	 * @param string $url
	 */
	public static function moved($url){
		header("HTTP/1.1 301 Moved Permanently");
		self::redirect($url);
	}

	/**
	 * sends the user back to where he came ;)
	 * the $redirect param is a fallback
	 *
	 * @param string $redirect
	 */
	public static function historyBack($redirect = '/'){
		if(true === isset($_SERVER['HTTP_REFERER'])){
			$redirect = $_SERVER['HTTP_REFERER'];
		}
		self::redirect($redirect);
	}

	/**
	 * reloads the current site
	 */
	public static function refresh(){
		self::redirect();
	}

	/**
	 * grab the host name
	 * if it was set before
	 * saves it in the default stack
	 */
	public static function grabHostName(){
		Dependency::get('applicationConfiguration')->set('hostname', php_uname("n"));
	}

	/**
	 * grabs the version and mode of the application
	 * it is grabbed from the apache directive
	 * SetEnv APPLICATION_ENV "main-dev"
	 * version 	€{main, mobile, ..}
	 * mode 		€{production, dev, ..}
	 * saves it in the default stack
	 */
	public static function grabModeAndVersion(){
		if(false === getenv('APPLICATION_ENV')){
			putenv('APPLICATION_ENV=main-dev');
		}
		$split = explode('-', getenv('APPLICATION_ENV'));
		$version = $split[0];
		$mode = $split[1];
		Dependency::get('applicationConfiguration')->set('version', $version);
		Dependency::get('applicationConfiguration')->set('mode', $mode);
	}
}
