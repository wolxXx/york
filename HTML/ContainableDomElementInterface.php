<?php
namespace York\HTML;
/**
 * interface for html elements that can contain other elements
 *
 * @author wolxXx
 * @version 3.0
 * @package \York\HTML;
 */
interface ContainableDomElementInterface extends \York\HTML\DomElementInterface{

	/**
	 * adds a child to the element
	 *
	 * @param \York\HTML\ContainableDomElementInterface | \York\HTML\DomElementInterface $child
	 * @return \York\HTML\ContainableDomElementInterface
	 */
	public function addChild(\York\HTML\DomElementInterface $child);

	/**
	 * adds children to the children array
	 *
	 * @param array $children
	 * @return \York\HTML\ContainableDomElementInterface
	 */
	public function addChildren($children);

	/**
	 * returns all children
	 *
	 * @return \York\HTML\DomElementInterface[]
	 */
	public function getChildren();
}
