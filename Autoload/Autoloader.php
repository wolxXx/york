<?php
/**
 * object orientated autoloader
 *
 * @author wolxXx
 * @version 2.0
 * @package wolxXxMVC
 */
class AutoLoader{
	/**
	 * the found paths in the application directory
	 *
	 * @var array
	 */
	public static $paths = array();

	/**
	 * default classes are classes that do not really need to be extended
	 *
	 * @var array
	 */
	protected $defaultClasses = array(
		'Helper', 'Bootstrap', 'Model', 'HTML', 'Translator', 'ApiStatus'
	);

	/**
	 * @var string
	 */
	protected static $path = null;

	/**
	 * ignore these paths
	 *
	 * @var array
	 */
	protected $excludePaths = array(
		'',
		'.',
		'..',
		'.svn',
		'York',
	);

	/**
	 * constructor
	 * grabs the paths, registers spl autoloader
	 *
	 * if the $path param is null it takes get current working directory!
	*/
	public function __construct($path = null){
		if(null === $path){
			$path = __DIR__.'/../../../Library';
		}
		$path = realpath($path).'/';
		self::$path = $path;
		if(false === isset($_SERVER['DOCUMENT_ROOT']) || '' === $_SERVER['DOCUMENT_ROOT']){
			$_SERVER['DOCUMENT_ROOT'] = self::$path;
		}
		$this->grabPaths(self::$path, true);
		self::$paths = array_unique(self::$paths);
		rsort(self::$paths);
		spl_autoload_register(array($this, 'loadClass'), true);
	}

	/**
	 * scans the application directory for subdirectories
	 *
	 * @param string $directory
	 * @param boolean $ignoreExclude
	 */
	protected function grabPaths($directory, $ignoreExclude = false){
		if(false === is_dir($directory)){
			return;
		}

		if(false === $ignoreExclude && true === in_array(basename($directory), $this->excludePaths)){
			return;
		}

		self::$paths[] = $directory;
		foreach(scandir($directory) as $current){
			if(true === in_array($current, array('.', '..'))){
				continue;
			}
			$current = self::$path.$current;
			if(false === is_dir($current)){
				continue;
			}
			if(true === in_array(basename($current), $this->excludePaths)){
				continue;
			}
			$this->grabPaths($current);
		}
	}

	/**
	 * determines if the requested class name can be loaded via the autoloader
	 *
	 * @param string $className
	 * @return boolean
	 */
	public static function isLoadable($className){
		foreach(self::$paths as $path){
			if(
				true === is_file("$path/$className.php") ||
				true === is_file($path.'/'.strtolower($className).'.php') ||
				true === is_file($path.'/'.ucfirst($className).'.php')
			){
				return true;
			}
		}
		return false;
	}

	/**
	 * loads a requested file
	 * checks for default classes
	 *
	 * @param string $className
	 * @throws Exception
	 */
	public function loadClass($className){
		$className = explode('\\', $className);
		$className = $className[sizeof($className) - 1];
		foreach(self::$paths as $path){
			$file = sprintf('%s/%s.php',$path, $className);
			if(true === is_file($file)){
				require_once $file;
				return;
			}

			$file = sprintf('%s/%s.php',$path, strtolower($className));
			if(true === is_file($file)){
				require_once $file;
				return;
			}

			$file = sprintf('%s/%s.php',$path, ucfirst($className));
			if(true === is_file($file)){
				require_once $file;
				return;
			}
		}
	}
}