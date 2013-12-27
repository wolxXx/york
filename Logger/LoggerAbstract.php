<?php
namespace York\Logger;
use York\Exception\LoggerLevelNotAllowed;

/**
 * abstract class for all loggers
 *
 * @author wolxXx
 * @version 3.0
 * @package York\Logger
 */
abstract class LoggerAbstract implements LoggerInterface{
	/**
	 * levels of the logger
	 *
	 * @var string[]
	 */
	protected $levels = array();

	/**
	 * clears all levels
	 *
	 * @return \York\Logger\LoggerAbstract
	 */
	public function clearLevels(){
		$this->levels = array();
		return $this;
	}

	/**
	 * overwrites all levels
	 *
	 * @param string[] $levels
	 * @return \York\Logger\LoggerAbstract
	 */
	public function setLevels($levels = array()){
		$this->clearLevels();
		foreach($levels as $current){
			$this->addLevel($current);
		}
		return $this;
	}

	/**
	 * adds a level
	 * @param string $level
	 * @return \York\Logger\LoggerAbstract
	 * @throws LoggerLevelNotAllowed
	 */
	public function addLevel($level){
		if(false === Manager::getInstance()->isAllowedLevel($level)){
			throw new LoggerLevelNotAllowed();
		}
		if(false === in_array($level, $this->levels)){
			$this->levels[] = $level;
		}
		return $this;
	}

	/**
	 * setter for the level
	 *
	 * @param string $level
	 * @throws LoggerLevelNotAllowed
	 * @return \York\Logger\LoggerAbstract
	 */
	public function setLevel($level){
		return $this
			->clearLevels()
			->addLevel($level);
	}

	/**
	 * getter for the level
	 *
	 * @return string[
	 */
	public function getLevels(){
		return $this->levels;
	}
}
