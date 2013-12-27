<?php
namespace York\Dependency;
use York\Exception\Dependency;
use York\Helper\Application as ApplicationHelper;
use York\Helper\Set;

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
	private static function getInstance(){
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
		return ApplicationHelper::getApplicationRoot().'Configuration/dependency';
	}

	/**
	 * try to parse the given configuration file
	 * if the given file does not exist, an empty array will be returned
	 *
	 * @param $pathToFile
	 * @return array
	 * @throws Dependency
	 */
	protected function parseConfigurationFile($pathToFile){
		if(false === file_exists($pathToFile)){
			return array();
		}

		$configuration = @parse_ini_file($pathToFile, true, INI_SCANNER_NORMAL);

		if(false === $configuration){
			throw new Dependency(sprintf('unable to parse %s', $pathToFile));
		}

		return $configuration;
	}

	/**
	 * @throws Dependency
	 */
	protected function __construct(){
		$this->instances = array();
		$this->configuration = Set::merge(
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
	 * @return \York\Dependency\Manager
	 */
	public static function setDependency($type, &$object){
		$manager = self::getInstance();
		$manager->instances[$type] = $object;
		$manager->configuration[$type]['class'] = get_class($object);
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
	 * checks if the selected class is defined as singleton class
	 *
	 * @param $type
	 * @return boolean
	 */
	protected function isShared($type){
		return isset($this->configuration[$type]['shared']) && '1' === $this->configuration[$type]['shared'];
	}

	public static function getClassNameForDependency($type){
		$manager = self::getInstance();
		if(false === $manager->hasDependencyConfigured($type)){
			throw new Dependency(sprintf('unable to get dependency %s', $type));
		}
		if(false === isset($manager->configuration[$type]['class'])){
			throw new Dependency(sprintf('not fully qualified dependency %s', $type));
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
	 * @throws Dependency
	 */
	public static function get($type){
		$manager = self::getInstance();
		$className = self::getClassNameForDependency($type);

		if(false === $manager->isShared($type)){
			return new $className();
		}

		if(false === $manager->hasDependencyInstantiated($type)){
			$manager->setDependency($type, new $className());
		}

		return $manager->instances[$type];
	}
}
