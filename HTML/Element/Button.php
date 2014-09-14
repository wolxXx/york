<?php
namespace York\HTML\Element;
/**
 * a button element
 *
 * @author wolxXx
 * @version 3.0
 * @package York\HTML\Element
 */
class Button extends \York\HTML\DomElementAbstract{
	/**
	 * @param array $data
	 * @return \York\HTML\Element\Button
	 */
	public static function Factory($data = array()){
		return parent::Factory($data);
	}

	/**
	 * @inheritdoc
	 */
	public static function getDefaultConf(){
		return array(
			'text' => 'ok'
		);
	}

	/**
	 * setter for the text
	 *
	 * @param string $text
	 * @return \York\HTML\Element\Button
	 */
	public function setText($text){
		$this->set('text', $text);

		return $this;
	}

	/**
	 * @inheritdoc
	 */
	public function display(){
		$conf = $this->getConf();
		$text = $conf['text'];
		unset($conf['text']);

		\York\HTML\Core::out(
			\York\HTML\Core::openTag('button', $conf),
			$text,
			\York\HTML\Core::closeTag('button')
		);

		return $this;
	}
}
