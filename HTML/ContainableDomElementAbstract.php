<?php
namespace York\HTML;
/**
 * abstract class for providing containable elements
 *
 * @author wolxXx
 * @version 3.0
 * @package \York\HTML;
 */
abstract class ContainableDomElementAbstract extends \York\HTML\DomElementAbstract implements \York\HTML\ContainableDomElementInterface{
	/**
	 * elements in the grid, textareas, inputs, etc
	 *
	 * @var \York\HTML\DomElementAbstract[]
	 */
	protected $children = array();

	/**
	 * (non-PHPdoc)
	 * @see ContainableDomElementInterface::addChild()
	 */
	public function addChild(DomElementInterface $child){
		$this->children[] = $child;
		return $this;
	}

	/**
	 * (non-PHPdoc)
	 * @see ContainableDomElementInterface::addChildren()
	 */
	public function addChildren($children = array()){
		foreach($children as $current){
			$this->addChild($current);
		}
		return $this;
	}

	/**
	 * (non-PHPdoc)
	 * @see ContainableDomElementInterface::getChildren()
	 */
	public function getChildren(){
		return $this->children;
	}
}