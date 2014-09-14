<?php
namespace York\View\Splash;
/**
 * a splash item
 *
 * @author wolxXx
 * @version 3.0
 * @package York\View\Splash
 */
class Item implements \York\View\Splash\ItemInterface{
	/**
	 * the displayed text
	 *
	 * @var string
	 */
	protected $text;

	/**
	 * create a new splash instance
	 *
	 * @param string $text
	 */
	public function __construct($text = ''){
		$this->setText($text);
	}

	/**
	 * setter for the text
	 *
	 * @param $text
	 * @return ItemInterface
	 */
	public function setText($text){
		$this->text = $text;
		return $this;
	}

	/**
	 * getter for the text
	 *
	 * @return string
	 */
	public function getText(){
		return $this->text;
	}
}
