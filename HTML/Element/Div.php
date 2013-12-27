<?php
namespace York\HTML\Element;
/**
 * a div element
 *
 * @author wolxXx
 * @version 3.0
 * @package York\HTML\Element
 */
class Div extends \York\HTML\ContainableDomElementAbstract{
	/**
	 * @param array $data
	 * @return \York\HTML\Element\Container
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
		);
	}

	/**
	 * (non-PHPdoc)
	 * @see DomElementInterface::getDefaultConf()
	 */
	public function display(){
		$conf = $this->getConf();

		\York\HTML\Core::out(\York\HTML\Core::openTag('div', $conf));
		/**
		 * @var \York\HTML\DomElementInterface $current
		 */
		foreach($this->children as $current){
			$current->display();
		}
		\York\HTML\Core::out(\York\HTML\Core::closeTag('div'));
		return $this;
	}
}