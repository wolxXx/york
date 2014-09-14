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
	 * @inheritdoc
	 */
	public static function getDefaultConf(){
		return array(
			'name' => null,
			'value' => \York\Helper\Translator::translate('Abschicken'),
			'type' => 'submit'
		);
	}

	/**
	 * @inheritdoc
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
	 * @inheritdoc
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
