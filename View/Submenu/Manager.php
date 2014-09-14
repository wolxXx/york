<?php
namespace York\View\Submenu;
/**
 * the submenu item manager
 *
 * @author wolxXx
 * @version 3.0
 * @package York\View\Submenu
 */
class Manager{
	/**
	 * the one and only instance
	 *
	 * @var \York\View\Submenu\Manager
	 */
	protected static $instance = null;

	/**
	 * the submenu item containing array
	 *
	 * @var array
	 */
	protected $items = array();

	/**
	 * getter for the instance
	 *
	 * @return \York\View\Submenu\Manager
	 */
	public static function getInstance(){
		if(null === self::$instance){
			self::$instance = new self();
		}
		return self::$instance;
	}

	/**
	 * private constructor in sense of singleton pattern
	 */
	private function __construct(){
		$this->items = array();
	}

	/**
	 * adds a submenuitem to the container
	 *
	 * @param \York\View\Submenu\Item $item
	 * @return \York\View\Submenu\Manager
	 */
	public function addItem(\York\View\Submenu\Item $item){
		$this->items[] = $item;
		return $this;
	}

	/**
	 * returns all set submenu items
	 *
	 * @return array
	 */
	public function getAllItems(){
		return $this->items;
	}

	/**
	 * renders the submenu if it contains some elements
	 * @return \York\View\Submenu\Manager
	 */
	public function display(){
		if(true === empty($this->items)){
			return $this;
		}
		$div = \York\HTML\Element\Div::Factory();
		$div->setId('submenu');
		/**
		 * @var \York\View\Submenu\Item $current
		 */
		foreach($this->items as $current){
			$div->addChild(\York\HTML\Element\Plaintext::Factory(array('text' => $current->getOutput())));
		}
		$div->display();
		return $this;
	}
}
