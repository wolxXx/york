<?php
namespace York\HTML\Element;
/**
 * a headline element
 *
 * @author wolxXx
 * @version 3.0
 * @package York\HTML\Element;
 */
class Headline extends \York\HTML\DomElementAbstract{
	/**
	 * @param array $data
	 * @return \York\HTML\Element\Headline
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
			'size' => 1,
			'text' => ''
		);
	}

	/**
	 * setter for the size
	 *
	 * @param integer $size
	 * @return \York\HTML\Element\Headline
	 */
	public function setSize($size){
		$this->set('size', $size);
		return $this;
	}

	/**
	 * setter for the text
	 *
	 * @param string $text
	 * @return \York\HTML\Element\Headline
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

		$size = $conf['size'];
		unset($conf['size']);

		\York\HTML\Core::out(
			\York\HTML\Core::openTag('h'.$size, $conf),
			$text,
			\York\HTML\Core::closeTag('h'.$size)
		);

		return $this;
	}
}