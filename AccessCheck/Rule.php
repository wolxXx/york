<?php
namespace York\AccessCheck;
/**
 * sets the access rules for one action
 *
 * @author wolxXx
 * @version 3.0
 * @package York\AccessCheck
 */
class Rule{
	/**
	 * the action name
	 *
	 * @var string
	 */
	protected $actionName;

	/**
	 * if the user must be logged in
	 *
	 * @var boolean
	 */
	protected $authNeeded;

	/**
	 * the user level required
	 *
	 * @var integer
	 */
	protected $levelNeeded;

	/**
	 * constructor
	 *
	 * @param string $actionName
	 * @param boolean $authNeeded
	 * @param integer $levelNeeded
	 */
	public function __construct($actionName = '*', $authNeeded = false, $levelNeeded = 0){
		$this
			->setActionName($actionName)
			->setAuthNeeded($authNeeded)
			->setLevelNeeded($levelNeeded);
	}

	/**
	 * setter for the action name
	 *
	 * @param string $actionName
	 * @return \York\AccessCheck\Rule
	 */
	public function setActionName($actionName){
		$this->actionName = $actionName;
		return $this;
	}

	/**
	 * getter for the action name
	 *
	 * @return string
	 */
	public function getActionName(){
		return $this->actionName;
	}

	/**
	 * setter for auth needed
	 *
	 * @param boolean $authNeeded
	 * @return \York\AccessCheck\Rule
	 */
	public function setAuthNeeded($authNeeded){
		$this->authNeeded = $authNeeded;
		return $this;
	}

	/**
	 * getter for auth needed
	 *
	 * @return boolean
	 */
	public function isAuthNeeded(){
		return $this->authNeeded;
	}

	/**
	 * setter for the needed level
	 *
	 * @param integer $levelNeeded
	 * @return \York\AccessCheck\Rule
	 */
	public function setLevelNeeded($levelNeeded){
		$this->levelNeeded = $levelNeeded;
		return $this;
	}

	/**
	 * getter for the needed level
	 *
	 * @return integer
	 */
	public function getLevelNeeded(){
		return $this->levelNeeded;
	}
}