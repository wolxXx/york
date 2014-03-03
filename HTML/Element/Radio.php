<?php
namespace York\HTML\Element;
/**
 * a radio element container
 *
 * @author wolxXx
 * @version 3.0
 * @package York\HTML\Element
 */
class Radio extends \York\HTML\ContainableDomElementAbstract{
	/**
	 * @param array $data
	 * @return \York\HTML\Element\Radio
	 */
	public static function Factory($data = array()){
		return parent::Factory($data);
	}

	/**
	 * overwrites the abstract method
	 * only radio options are allowed as children
	 *
	 * @param \York\HTML\DomElementInterface $child
	 * @throws \York\Exception\HTTMLGenerator
	 * @return \York\HTML\Element\Radio
	 */
	public function addChild(\York\HTML\DomElementInterface $child){
		if($child instanceof \York\HTML\Element\RadioOption){
			$child->setName($this->get('name'));
			parent::addChild($child);
			return $this;
		}
		throw new \York\Exception\HTTMLGenerator('radio elements can only contain radio options as children');
	}

	/**
	 * (non-PHPdoc)
	 * @see DomElementInterface::display()
	 */
	public function display(){
		foreach($this->children as $current){
			$current->display();
		}
		return $this;
	}
}