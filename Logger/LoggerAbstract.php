<?php
namespace York\Logger;
/**
 * abstract class for all loggers
 *
 * @author wolxXx
 * @version 3.0
 * @package York\Logger
 */
abstract class LoggerAbstract implements \York\Logger\LoggerInterface{
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
	 * @return $this
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
	 *
	 * @param string $level
	 * @return \York\Logger\LoggerAbstract
	 * @throws \York\Exception\LoggerLevelNotAllowed
	 */
	public function addLevel($level){
		if(false === \York\Dependency\Manager::get('logger')->isAllowedLevel($level)){
			throw new \York\Exception\LoggerLevelNotAllowed();
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

	/**
	 * @param integer $level
	 * @return boolean
	 */
	public function hasLevel($level){
		return true === in_array($level, $this->levels);
	}
}
