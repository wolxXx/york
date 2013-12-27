<?php
namespace York\HTML\Element;
/**
 * plaintext
*
* @author wolxXx
* @version 3.0
* @package York\HTML\Element
*/
class Plaintext extends \York\HTML\DomElementAbstract{
	/**
	 * @param array $data
	 * @return \York\HTML\Element\Plaintext
	 */
	public static function Factory($data = array()){
		return parent::Factory($data);
	}

	/**
	 * adds text to the given text
	 * or creates one if nothing was set before
	 *
	 * @param string $text
	 * @return \York\HTML\Element\Plaintext
	 */
	public function addText($text){
		if(true === $this->hasKey('text')){
			$text = $this->get(('text')).' '.$text;
		}
		return $this->set('text', $text);
	}

	/**
	 * setter for the text
	 *
	 * @param string $text
	 * @return \York\HTML\Element\Plaintext
	 */
	public function setText($text){
		return $this->set('text', $text);
	}

	/**
	 * (non-PHPdoc)
	 * @see DomElementInterface::display()
	 */
	public function display(){
		\York\HTML\Core::out($this->get('text'));
		return $this;
	}
}