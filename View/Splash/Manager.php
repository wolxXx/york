<?php
namespace York\View\Splash;
/**
 * splash manager
 *
 * @author wolxXx
 * @version 3.0
 * @package York\Splash
 */
class Manager {
	/**
	 * singleton instance of the manager
	 *
	 * @var \York\View\Splash\Manager
	 */
	protected static $instance;

	/**
	 * set of splashes
	 *
	 * @var \York\View\Splash\ItemInterface[]
	 */
	protected $splashes = array();

	/**
	 * getter for the singleton instance
	 *
	 * @return \York\View\Splash\Manager
	 */
	public static function getInstance(){
		if(null === self::$instance){
			self::$instance = new self();
		}
		return self::$instance;
	}

	/**
	 * retrieves all set splashes
	 *
	 * @return \York\View\Splash\ItemInterface[]
	 */
	public function getSplashes(){
		return $this->splashes;
	}

	/**
	 * shortcut for add new splash
	 *
	 * @param $text
	 */
	public static function addText($text){
		self::getInstance()->addSplash(new \York\View\Splash\Item($text));
	}

	/**
	 * adds a splash to the splash set
	 * you can append (default) or prepend (set append to false) the splash
	 *
	 * @param \York\View\Splash\ItemInterface $splash
	 * @param boolean $append
	 * @return \York\View\Splash\Manager
	 */
	public function addSplash(\York\View\Splash\ItemInterface $splash, $append = true){
		if(true === $append){
			$this->splashes[] = $splash;
			return $this;
		}
		$this->splashes = array_unshift($this->splashes, $splash);
		return $this;
	}

	/**
	 * clears all set splashes
	 *
	 * @return \York\View\Splash\Manager
	 */
	public function clearSplashes(){
		$this->splashes = array();
		return $this;
	}
}
