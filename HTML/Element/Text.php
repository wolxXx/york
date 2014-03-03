<?php
namespace York\HTML\Element;
/**
 * a input element
*
* @author wolxXx
 * @version 3.0
 * @package York\HTML\Element
*/
class Text extends \York\HTML\Element\Input{
	/**
	 * @param array $data
	 * @return \York\HTML\Element\Text
	 */
	public static function Factory($data = array()){
		return parent::Factory($data);
	}
	/**
	 * @param $text
	 * @return \York\HTML\Element\Text
	 */
	public function setText($text){
		$this->set('text', $text);
		return $this;
	}
}