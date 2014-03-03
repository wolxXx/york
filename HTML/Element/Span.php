<?php
namespace York\HTML\Element;
/**
 * a span element
 *
 * @author wolxXx
 * @version 3.0
 * @package York\HTML\Element
 */
class Span extends \York\HTML\DomElementAbstract{
	/**
	 * @param array $data
	 * @return \York\HTML\Element\Span
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
			'class' => null,
			'text' => ''
		);
	}

	/**
	 * setter for the text
	 *
	 * @param string $text
	 * @return \York\HTML\Element\Span
	 */
	public function setText($text){
		$this->set('text', $text);
		return $this;
	}

	/**
	 * (non-PHPdoc)
	 * @see DomElementInterface::display()
	 */
	public function display(){
		$conf = $this->getConf();
		$text = $conf['text'];
		unset($conf['text']);

		\York\HTML\Core::out(
			\York\HTML\Core::openTag('span', $conf),
			$text,
			\York\HTML\Core::closeTag('span')
		);

		return $this;
	}
}