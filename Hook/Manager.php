<?php
namespace York\Hook;

/**
 * hook manager
 *
 * @author wolxXx
 * @version 3.0
 * @package York\Hook
 */
class Manager {
	/**
	 * no priority
	 */
	const PRIORITY_NONE = 0;

	/**
	 * minor priority
	 */
	const PRIORITY_MINOR = 1;

	/**
	 * normal priority
	 */
	const PRIORITY_NORMAL = 2;

	/**
	 * major priority
	 */
	const PRIORITY_MAJOR = 3;

	/**
	 * critical priority
	 */
	const PRIORITY_CRITICAL = 4;

	/**
	 * @var \York\Hook\Item[]
	 */
	protected $hooks;

	/**
	 * setup
	 */
	public function __construct(){
		$this->clearHooks();
	}

	/**
	 * clear all hooks
	 *
	 * @return $this
	 */
	public function clearHooks(){
		$this->hooks = array();
		$this->hooks[self::PRIORITY_NONE] = array();
		$this->hooks[self::PRIORITY_MINOR] = array();
		$this->hooks[self::PRIORITY_NORMAL] = array();
		$this->hooks[self::PRIORITY_MAJOR] = array();
		$this->hooks[self::PRIORITY_CRITICAL] = array();

		return $this;
	}

	/**
	 * add a hook
	 *
	 * @param \York\Hook\Item $hook
	 * @return $this
	 */
	public function addHook(\York\Hook\Item $hook){
		$this->hooks[$hook->getPriority()][] = $hook;

		return $this;
	}

	/**
	 * call all hooks for given event
	 *
	 * @param string $event
	 * @return $this
	 */
	public function call($event){
		foreach($this->getHooksForEvent($event) as $hook){
			$hook->run();
		}

		return $this;
	}

	/**
	 * get all hooks
	 *
	 * @return \York\Hook\Item[]
	 */
	public function getHooks(){
		return $this->hooks;
	}

	/**
	 * get all hooks for given event
	 * ordered by their priority
	 *
	 * @param string $event
	 * @return \York\Hook\Item[]
	 */
	public function getHooksForEvent($event){
		$return = array();
		foreach($this->hooks as $hooks){
			foreach($hooks as $hook){
				/**
				 * @var \York\Hook\Item $hook
				 */
				if($event === $hook->getEvent()){
					$return[] = $hook;
				}
			}
		}

		return $return;
	}
}
