<?php
namespace York\HTML\Element;
/**
 * a submit button element
*
* @author wolxXx
 * @version 3.0
 * @package York\HTML\Element
*/
class Submit extends \York\HTML\DomElementAbstract{
	/**
	 * @param array $data
	 * @return \York\HTML\Element\Submit
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
			'name' => null,
			'value' => \York\Helper\Translator::translate('Abschicken'),
			'type' => 'submit'
		);
	}

	/**
	 * removes the name property as default behaviour
	 * but the property can be set later if wanted!
	 *
	 * (non-PHPdoc)
	 * @see DomElementAbstract::init()
	 */
	public function init(){
		parent::init();
		$this->removeData('name');
	}

	/**
	 * sets the value of this button
	 *
	 * @param string $value
	 * @return \York\HTML\Element\Submit
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
		$this->displayLabelBefore();

		$conf = $this->getConf();
		\York\HTML\Core::out(
			\York\HTML\Core::openSingleTag('input', $conf),
			\York\HTML\Core::closeSingleTag()
		);

		$this->displayLabelAfter();
		return $this;
	}
}