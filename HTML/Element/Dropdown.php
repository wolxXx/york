<?php
namespace York\HTML\Element;
/**
 * a dropdown element container
 *
 * @author wolxXx
 * @version 3.0
 * @package \York\HTML\Element
 */
class Dropdown extends \York\HTML\ContainableDomElementAbstract{
	/**
	 * @param array $data
	 * @return \York\HTML\Element\Dropdown
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
	 * overwrites the abstract method
	 * it only accepts dropdown elements or dropdown groups
	 *
	 * @param \York\HTML\DomElementInterface|\York\HTML\Element\DropdownElement|\York\HTML\Element\DropdownGroup $child
	 * @throws \York\Exception\HTTMLGenerator
	 * @return \York\HTML\Element\Dropdown
	 */
	public function addChild(\York\HTML\DomElementInterface $child){
		if($child instanceof \York\HTML\Element\DropdownElement || $child instanceof \York\HTML\Element\DropdownGroup){
			parent::addChild($child);
			return $this;
		}
		throw new \York\Exception\HTTMLGenerator('dropdown container can only contain dropdown elements or groups as children');
	}

	/**
	 * (non-PHPdoc)
	 * @see DomElementInterface::getDefaultConf()
	 */
	public function display(){
		$this->displayLabelBefore();

		$conf = $this->getConf();

		\York\HTML\Core::out(
			\York\HTML\Core::openTag('select', $conf)
		);


		/**
		 * @var \York\HTML\DomElementInterface $current
		 */
		foreach($this->children as $current){
			$current->display();
		}

		\York\HTML\Core::out(
			\York\HTML\Core::closeTag('select')
		);


		$this->displayLabelAfter();

		return $this;
	}
}