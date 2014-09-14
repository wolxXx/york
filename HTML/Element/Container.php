<?php
namespace York\HTML\Element;
/**
 * alias for div element
 *
 * @author wolxXx
 * @version 3.0
 * @package \York\HTML\Element
 */
class Container extends \York\HTML\Element\Div{
	/**
	 * @param array $data
	 * @return \York\HTML\Element\Container
	 */
	public static function Factory($data = array()){
		return parent::Factory($data);
	}
}
