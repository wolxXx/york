<?php
namespace York\HTML\Element;
/**
 * a single dropdown element
 *
 * @author wolxXx
 * @version 3.0
 * @package \York\HTML\Element
 */
class DropdownElement extends \York\HTML\DomElementAbstract{
	/**
	 * @param array $data
	 * @return \York\HTML\Element\DropdownElement
	 */
	public static function Factory($data = array()){
		return parent::Factory($data);
	}

	/**
	 * @inheritdoc
	 */
	public static function getDefaultConf(){
		return array(
			'selected' => null
		);
	}

	/**
	 * setter for is selected
	 *
	 * @param boolean $isSelected
	 * @return \York\HTML\Element\DropdownElement
	 */
	public function setIsSelected($isSelected = true){
		$this->set('selected', true === $isSelected? 'selected' : null);

		return $this;
	}

	/**
	 * setter for the value
	 *
	 * @param string $value
	 * @return \York\HTML\Element\DropdownElement
	 */
	public function setValue($value){
		$this->set('value', $value);

		return $this;
	}

	/**
	 * setter for the text
	 *
	 * @param string $text
	 * @return \York\HTML\Element\DropdownElement
	 */
	public function setText($text){
		$this->set('text', $text);

		return $this;
	}

	/**
	 * sets value and text
	 *
	 * @param string $value
	 * @return \York\HTML\Element\DropdownElement
	 */
	public function setValueAndText($value){
		return $this
			->setText($value)
			->setValue($value)
		;
	}

	/**
	 * @inheritdoc
	 */
	public function display(){
		$conf = $this->getConf();
		$text = $conf['text'];
		unset($conf['text']);
		\York\HTML\Core::out(
			\York\HTML\Core::openTag('option', $conf),
			$text,
			\York\HTML\Core::closeTag('option')
		);

		return $this;
	}
}
