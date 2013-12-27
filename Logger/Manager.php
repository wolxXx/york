<?php
namespace York\Logger;
use York\Code\SingletonWithoutShutdown;

/**
 * log manager
 *
 * @author wolxXx
 * @version 3.0
 * @package York\Logger
 */

class Manager extends SingletonWithoutShutdown{
	/**
	 * list of listening loggers
	 *
	 * @var \York\Logger\LoggerInterface
	 */
	protected $registeredLoggers;

	/**
	 * initialize the logger manager
	 * get your instance via getInstance
	 */
	public function __construct(){
		$this->registeredLoggers = array();
	}

	/**
	 * add a logger to the list of listening loggers
	 *
	 * @param LoggerInterface $logger
	 * @return \York\Logger\Manager
	 */
	public function addLogger(LoggerInterface $logger){
		$this->registeredLoggers[] = $logger;
		return $this;
	}

	/**
	 * checks if the given level is allowed
	 *
	 * @param string $level
	 * @return boolean
	 */
	public function isAllowedLevel($level){
		$reflection = new \ReflectionClass(__CLASS__);
		$allowed = array();
		foreach($reflection->getConstants() as $name => $value){
			if('LEVEL' === substr($name, 0, 5)){
				$allowed[] = $value;
			}
		}
		return in_array($level, $allowed);
	}

	/**
	 * log a message
	 *
	 * @param string $message
	 * @param string $level
	 * @return \York\Logger\Manager
	 */
	public final function log($message, $level = self::LEVEL_ALL){
		/**
		 * @var LoggerInterface $current
		 */
		foreach($this->registeredLoggers as $current){
			if(self::LEVEL_ALL === $level || true === in_array($level, $current->getLevels())){
				$current->log($message);
			}
		}
		return $this;
	}

	/**
	 * logger level all
	 *
	 * @var string
	 */
	const LEVEL_ALL = 'LEVEL_ALL';

	/**
	 * logger level warn
	 *
	 * @var string
	 */
	const LEVEL_WARN = 'LEVEL_WARN';

	/**
	 * logger level debug
	 *
	 * @var string
	 */
	const LEVEL_DEBUG = 'LEVEL_DEBUG';

	/**
	 * logger level error
	 *
	 * @var string
	 */
	const LEVEL_ERROR = 'LEVEL_ERROR';

	/**
	 * logger level notice
	 *
	 * @var string
	 */
	const LEVEL_NOTICE = 'LEVEL_NOTICE';

	/**
	 * logger level database error
	 *
	 * @var string
	 */
	const LEVEL_DATABASE_ERROR = 'LEVEL_DATABASE_ERROR';

	/**
	 * logger level database debug
	 *
	 * @var string
	 */
	const LEVEL_DATABASE_DEBUG = 'LEVEL_DATABASE_DEBUG';

	/**
	 * logger level log post
	 *
	 * @var string
	 */
	const LEVEL_LOG_POST = 'LEVEL_LOG_POST';

	/**
	 * logger level email send
	 *
	 * @var string
	 */
	const LEVEL_EMAIL = 'LEVEL_EMAIL';

	/**
	 * logger level email sending failed
	 *
	 * @var string
	 */
	const LEVEL_EMAIL_FAILED = 'LEVEL_EMAIL_FAILED';

	/**
	 * logger level york framework debug
	 *
	 * @var string
	 */
	const LEVEL_YORK_DEBUG = 'LEVEL_YORK_DEBUG';

	/**
	 * logger level application debug
	 *
	 * @var string
	 */
	const LEVEL_APPLICATION_DEBUG = 'LEVEL_APPLICATION_DEBUG';
}
