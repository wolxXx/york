<?php
namespace York\HTML\Element;
/**
 * a date picker element
 *
 * @author wolxXx
 * @version 3.0
 * @package York\HTML\Element
 */
class Time extends \York\HTML\Element\Date{
	/**
	* @param array $data
	* @return \York\HTML\Element\Time
	*/
	public static function Factory($data = array()){
		return parent::Factory($data);
	}

	/**
	 * (non-PHPdoc)
	 * @see DomElementInterface::display()
	 */
	public function display(){
		$this->set('format', '%Y-%m-%d %H:%M');
		return parent::display();
	}
}