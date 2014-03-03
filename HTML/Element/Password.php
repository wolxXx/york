<?php
namespace York\HTML\Element;
/**
 * a password input element
 *
 * @author wolxXx
 * @version 3.0
 * @package York\HTML\Element
*/
class Password extends \York\HTML\DomElementAbstract{
	/**
	 * @param array $data
	 * @return \York\HTML\Element\Password
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
			'name' => 'password',
			'type' => 'password'
		);
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