<?php
namespace York\HTML\Element;
/**
 * displays a break element
 *
 * @author wolxXx
 * @version 3.0
 * @package \York\HTML\Element
 */
class Br extends \York\HTML\DomElementAbstract{
	/**
	 * @param array $data
	 * @return \York\HTML\Element\Br
	 */
	public static function Factory($data = array()){
		return parent::Factory($data);
	}

	/**
	 * (non-PHPdoc)
	 * @see DomElementInterface::display()
	 */
	public function display(){
		\York\HTML\Core::out('');
		echo \York\HTML\Core::openSingleTag('br');
		echo \York\HTML\Core::closeSingleTag();
		\York\HTML\Core::out('');
	}
}