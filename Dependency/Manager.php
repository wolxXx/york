<?php
namespace York\Dependency;

/**
 * dependency manager
 *
 * @author wolxXx
 * @version 3.0
 * @package York\Dependency
 */
class Manager{
	/**
	 * own instance
	 *
	 * @var \York\Dependency\Manager
	 */
	private static $instance;

	/**
	 * all other instances
	 *
	 * @var array
	 */
	protected $instances;

	/**
	 * the configured dependencies
	 * @var array
	 */
	protected $configuration;

	/**
	 * @return \York\Dependency\Manager
	 */
	public static function getInstance(){
		if(null === self::$instance){
			self::$instance = new self();
		}
		return self::$instance;
	}

	/**
	 * get the path to the default configuration file
	 *
	 * @return string
	 */
	protected function getPathToDefaultConfiguration(){
		return __DIR__.'/default';
	}

	/**
	 * get the path to the application configuration file
	 *
	 * @return string
	 */
	protected function getPathToApplicationConfiguration(){
		return \York\Helper\Application::getApplicationRoot().'Configuration/dependency';
	}

	/**
	 * try to parse the given configuration file
	 * if the given file does not exist, an empty array will be returned
	 *
	 * @param $pathToFile
	 * @return array
	 * @throws \York\Exception\Dependency
	 */
	protected function parseConfigurationFile($pathToFile){
		if(false === file_exists($pathToFile)){
			return array();
		}

		$configuration = @parse_ini_file($pathToFile, true, INI_SCANNER_NORMAL);

		if(false === $configuration){
			throw new \York\Exception\Dependency(sprintf('unable to parse %s', $pathToFile));
		}

		return $configuration;
	}

	/**
	 * @throws \York\Exception\Dependency
	 */
	protected function __construct(){
		$this->instances = array();
		$this->configuration = \York\Helper\Set::merge(
			$this->parseConfigurationFile($this->getPathToDefaultConfiguration()),
			$this->parseConfigurationFile($this->getPathToApplicationConfiguration())
		);
	}

	/**
	 * checks if the type is configured
	 *
	 * @param $type
	 * @return boolean
	 */
	protected function hasDependencyConfigured($type){
		return isset($this->configuration[$type]) && isset($this->configuration[$type]['class']);
	}

	/**
	 * checks if an instance of the dependency exists
	 *
	 * @param $type
	 * @return boolean
	 */
	protected function hasDependencyInstantiated($type){
		return isset($this->instances[$type]);
	}

	/**
	 * setter for an instance
	 *
	 * @param $type
	 * @param $object
	 * @param bool $isShared
	 * @return $this
	 */
	public static function setDependency($type, &$object, $isShared = true){
		$manager = self::getInstance();
		$manager->instances[$type] = $object;
		$manager->configuration[$type]['class'] = get_class($object);
		$manager->configuration[$type]['shared'] = true === $isShared?  '1' : '0';

		return $manager;
	}


	/**
	 * getter for the current configuration
	 *
	 * @return array
	 */
	  protected function getConfiguration(){
		return $this->configuration;
	}

	/**
	 * @param string $type
	 * @return boolean
	 */
	public static function isShared($type){
		return isset(self::getInstance()->configuration[$type]['shared']) && '1' === self::getInstance()->configuration[$type]['shared'];
	}

	/**
	 * get the class name
	 *
	 * @param $type
	 * @return mixed
	 * @throws \York\Exception\Dependency
	 */
	public static function getClassNameForDependency($type){
		$manager = self::getInstance();

		if(false === $manager->hasDependencyConfigured($type)){
			throw new \York\Exception\Dependency(sprintf('unable to get dependency %s', $type));
		}

		if(false === isset($manager->configuration[$type]['class'])){
			throw new \York\Exception\Dependency(sprintf('not fully qualified dependency %s', $type));
		}

		return $manager->configuration[$type]['class'];
	}


	/**
	 * get an instance for the given type
	 * set $fresh to true to get a fresh and non-shared object
	 * otherwise it checks if an instance exists
	 * if not, it creates a new one
	 *
	 * if the configured class is declared as a singleton class,
	 * the getInstance function is called on the object
	 *
	 * @param string  $type
	 * @return mixed
	 * @throws \York\Exception\Dependency
	 */
	public static function get($type){
		$manager = self::getInstance();
		$className = self::getClassNameForDependency($type);

		if(false === self::isShared($type)){
			return new $className();
		}

		if(false === $manager->hasDependencyInstantiated($type)){
			$manager->setDependency($type, new $className());
		}

		return $manager->instances[$type];
	}

	/** @return \York\Request\Api\Code */
	public static function getApiCode()
	{
		return self::get('apiCode');
	}

	/** @return \York\Request\Manager */
	public static function getRequestManager()
	{
		return self::get('requestManager');
	}

	/** @return \York\Request\Data */
	public static function getRequestData()
	{
		return self::get('requestData');
	}

	/** @return \York\Database\Manager */
	public static function getDatabaseManager()
	{
		return self::get('databaseManager');
	}

	/** @return \York\Logger\Manager */
	public static function getLogger()
	{
		return self::get('logger');
	}

	/** @return \York\Storage\Session */
	public static function getSession()
	{
		return self::get('session');
	}

	/** @return \York\View\Splash\Manager */
	public static function getSplashManager()
	{
		return self::get('splashManager');
	}

	/** @return \York\View\Manager */
	public static function getViewManager()
	{
		return self::get('viewManager');
	}

	/** @return \York\Storage\Application */
	public static function getDatabaseConfiguration()
	{
		return self::get('databaseConfiguration');
	}

	/** @return \York\Storage\Application */
	public static function getApplicationConfiguration()
	{
		return self::get('applicationConfiguration');
	}

	/** @return \York\View\Asset\Manager */
	public static function getAssetManager()
	{
		return self::get('assetManager');
	}
}
