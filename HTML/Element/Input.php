<?php
namespace York\HTML\Element;

/**
 * a input element
*
* @author wolxXx
* @version 3.0
* @package York\HTML\Element;
*/
class Input extends \York\HTML\DomElementAbstract{
	/**
	 * @param array $data
	 * @return \York\HTML\Element\Input
	 */
	public static function Factory($data = array()){
		return parent::Factory($data);
	}

	/**
	 * @inheritdoc
	 */
	public static function getDefaultConf(){
		return array(
			'type' => 'text',
			'value' => null,
			'autocomplete' => null,
			'readonly' => null
		);
	}

	/**
	 * setter for the value
	 *
	 * @param string $value
	 * @return \York\HTML\Element\Input
	 */
	public function setValue($value){
		$this->set('value', $value);

		return $this;
	}

	/**
	 * setter for the type
	 *
	 * @param string $type
	 * @return \York\HTML\Element\Input
	 */
	public function setType($type){
		$this->set('type', $type);

		return $this;
	}

	/**
	 * disables the autocomplete
	 * or sends it to the browser and hopes it works...
	 *
	 * @return \York\HTML\Element\Input
	 */
	public function disableAutocomplete(){
		$this->set('autocomplete', 'off');

		return $this;
	}

	/**
	 * removes the html flag for disabling autocompletion
	 *
	 * @return \York\HTML\Element\Input
	 */
	public function enableAutocomplete(){
		$this->set('autocomplete', null);

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
