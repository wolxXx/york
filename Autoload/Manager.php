<?php
namespace York\Autoload;
use York\York;

/**
 * object orientated auto loading mechanism
 *
 * @author wolxXx
 * @version 3.0
 * @package wolxXxMVC
 */
final class Manager{
	/**
	 * constructor
	 * checks if there exists a map, creates it if not, and takes it
	 * grabs the paths, registers spl auto loader
	 *
	 * if the $path param is null it takes get current working directory!
	 */
	public function __construct(){
		spl_autoload_register(array($this, 'loadClass'), true, true);
		require_once __DIR__.'/Autoloader.php';
		new \AutoLoader();
	}

	/**
	 * retrieves the file path for the given class name
	 *
	 * @param $className
	 * @return string
	 */
	protected static function getResolvedPathForClassName($className){
		return str_replace('\\', '/', $className).'.php';
	}

	/**
	 * retrieves the file path for the given class name and prepends "Library"
	 *
	 * @param $className
	 * @return string
	 */
	protected static function getResolvedPathForClassNameInLibrary($className){
		return 'Library/'.self::getResolvedPathForClassName($className);
	}

	/**
	 * checker if the given className is loadable via this autoloader
	 *
	 * @param $className string
	 * @return boolean
	 */
	public static function isLoadable($className){
		if(true === file_exists(self::getResolvedPathForClassNameInLibrary($className))){
			return true;
		}
		if(true === file_exists(self::getResolvedPathForClassName($className))){
			return true;
		}
		return false;
	}

	/**
	 * loads a requested file
	 * checks for default classes
	 *
	 * @param string $className
	 */
	public function loadClass($className){
		$resolvedClassPathInLibrary = self::getResolvedPathForClassNameInLibrary($className);
		if(true === file_exists($resolvedClassPathInLibrary)){
			require_once $resolvedClassPathInLibrary;
			return;
		}

		$resolvedClassPath = self::getResolvedPathForClassName($className);
		if(true === file_exists($resolvedClassPath)){
			require_once $resolvedClassPath;
			return;
		}

		\York\Dependency\Manager::get('logger')->log(sprintf('unable to load "%s" via Autoload/Manager. loading deprecated legacy autoloader', $className), \York\Logger\Manager::LEVEL_DEBUG);
	}
}
