<?php
namespace York\HTML;
/**
 * interface for html elements
 *
 * @author wolxXx
 * @version 3.0
 * @package York\HTML
 */
interface DomElementInterface{
	/**
	 * calls the html element generator
	 *
	 * @return \York\HTML\DomElementInterface
	 */
	public function display();

	/**
	 * returns the default config for this element
	 *
	 * @return array
	 */
	public static function getDefaultConf();

	/**
	 * returns the ID of the element
	 *
	 * @return string
	 */
	public function getId();

	/**
	 * setter for id
	 *
	 * @param string $id
	 * @return \York\HTML\DomElementInterface
	 */
	public function setId($id);

	/**
	 * returns the name of the element
	 *
	 * @return string
	 */
	public function getName();

	/**
	 * setter for required flag
	 *
	 * @param boolean $required
	 * @return \York\HTML\DomElementInterface
	 */
	public function setIsRequired($required = true);

	/**
	 * factory function
	 *
	 * @param array $data
	 * @return \York\HTML\DomElementInterface
	 */
	public static function Factory($data = array());

	/**
	 * getter for the label
	 *
	 * @return \York\HTML\Element\Label
	 */
	public function getLabel();
}
