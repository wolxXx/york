<?php
namespace York\HTML\Element;
/**
 * a radio option element
 *
 * @author wolxXx
 * @version 3.0
 * @package York\HTML\Element
 */
class RadioOption extends \York\HTML\DomElementAbstract{
	/**
	 * @param array $data
	 * @return \York\HTML\Element\RadioOption
	 */
	public static function Factory($data = array()){
		return parent::Factory($data);
	}

	/**
	 * (non-PHPdoc)
	 * @see DomElementInterface::getDefaultConf()
	 */
	public static function getDefaultConf(){
		return array(
			'checked' => null,
			'type' => 'radio'
		);
	}

	/**
	 * setter for the value
	 *
	 * @param string $value
	 * @return \York\HTML\Element\RadioOption
	 */
	public function setValue($value){
		$this->set('value', $value);
		return $this;
	}

	/**
	 * (non-PHPdoc)
	 * @see DomElementInterface::display()
	 */
	public function display(){
		if(null === $this->label){
			$this->addLabel($this->get('value'), 'after');
		}
		$this->displayLabelBefore();

		$conf = $this->getConf();
		\York\HTML\Core::out(
			\York\HTML\Core::openSingleTag('input', $conf),
			\York\HTML\Core::closeSingleTag('input')
		);

		$this->displayLabelAfter();
		return $this;
	}
}