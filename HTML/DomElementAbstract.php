<?php
namespace York\HTML;
/**
 * abstract class for having a wrapper for dom elements
 *
 * @author wolxXx
 * @version 3.0
 * @package York\HTML
 */
abstract class DomElementAbstract extends Options implements \York\HTML\DomElementInterface{
	/**
	 * data array
	 * is used for tag properties like class, name, id, etc
	 *
	 * @var \York\HTML\Options
	 */
	protected $data;

	/**
	 * label for the dom element
	 *
	 * @var \York\HTML\Element\Label
	 */
	protected $label;


	/**
	 * creates a new dom html element
	 * fills all args into data object
	 *
	 * @param array $args
	 * @throws \York\Exception\HTTMLGenerator
	 * @return \York\HTML\DomElementAbstract
	 */
	public static function Factory($args = array()){
		$class = get_called_class();
		$object = new $class();
		$args = \York\Helper\Set::merge(\York\HTML\Core::getUniqueIdAndName(), $args);
		foreach($args as $key => $value){
			$object->set($key, $value);
		}
		return $object;
	}

	/**
	 * returns the default config for all elements
	 *
	 * @return array
	 */
	public static function getDefaultConf(){
		$default = \York\HTML\Core::getUniqueIdAndName();
		$default['class'] = null;
		$default['title'] = null;
		$default['style'] = null;
		$default['placeholder'] = null;
		$default['required'] = false;
		return $default;
	}

	/**
	 * merges the default array with the filled data array
	 *
	 * @return array
	 */
	public function getConf(){
		return \York\Helper\Set::merge($this->getDefaultConf(), $this->getData());
	}

	/**
	 * setter for the style
	 *
	 * @param string $style
	 * @return \York\HTML\DomElementAbstract
	 */
	public function setStyle($style = ''){
		$this->set('style', $style);
		return $this;
	}

	/**
	 * getter for the style
	 *
	 * @return string
	 */
	public function getStyle(){
		return $this->getSavely('style', '');
	}

	/**
	 * adds style information
	 *
	 * @param string $style
	 * @return \York\HTML\DomElementAbstract
	 */
	public function addStyle($style){
		return $this->set('style', $this->getStyle().' '.$style);
	}

	/**
	 * (non-PHPdoc)
	 * @see DomElementInterface::setIsRequired()
	 */
	public function setIsRequired($required = true){
		$this->set('required', true === $required);
		if(null !== $this->label){
			$this->label->addClass('required');
		}
		return $this;
	}

	/**
	 * getter for the required flag
	 *
	 * @return boolean
	 */
	public function getIsRequired(){
		return $this->get('required');
	}

	/**
	 * adds a label to the element
	 * the text param will be the visible text for the label
	 * position determines if the label shall be displayed before or after element
	 *
	 * @param string $text
	 * @param string $position
	 * @return \York\HTML\DomElementAbstract
	 */
	public function addLabel($text = null, $position = 'before'){
		$this->label = \York\HTML\Element\Label::Factory()
			->setText($text)
			->setFor($this->getId())
			->setPosition($position)
		;
		return $this;
	}

	/**
	 * getter for the label
	 *
	 * @return \York\HTML\Element\Label
	 */
	public function getLabel(){
		return $this->label;
	}

	/**
	 * setter for the class
	 *
	 * @param string $class
	 * @return \York\HTML\DomElementAbstract
	 */
	public function setClass($class){
		$this->set('class', $class);
		return $this;
	}

	/**
	 * adds a class to the element
	 *
	 * @param string $class
	 * @return \York\HTML\DomElementAbstract | \York\HTML\ContainableDomElementInterface
	 */
	public function addClass($class){
		if(true === $this->hasKey('class')){
			$class .= ' '.$this->get('class');
		}

		$this->set('class', $class);
		return $this;
	}

	/**
	 * adds given classes array
	 *
	 * @param array $classes
	 * @return \York\HTML\DomElementAbstract
	 */
	public function addClasses($classes){
		foreach($classes as $current){
			$this->addClass($current);
		}
		return $this;
	}

	/**
	 * sets the label with an instantiated object
	 *
	 * @param \York\HTML\Element\Label $label
	 * @return DomElementAbstract
	 */
	public function setLabel(\York\HTML\Element\Label $label){
		$this->label = $label;
		return $this;
	}

	/**
	 * removes a key from the data object
	 *
	 * @param string $key
	 * @return \York\HTML\DomElementAbstract
	 */
	public function removeProperty($key){
		$this->removeData($key);
		return $this;
	}

	/**
	 * setter for the name property
	 *
	 * @param string $name
	 * @return \York\HTML\DomElementInterface | \York\HTML\ContainableDomElementInterface
	 */
	public function setName($name){
		$this->set('name', $name);
		return $this;
	}

	/**
	 * setter for the id of the element
	 *
	 * @param string $elemId
	 * @return \York\HTML\DomElementAbstract
	 */
	public function setId($elemId){
		$this->set('id', $elemId);
		return $this;
	}


	/**
	 * sets the id and the name of this element
	 *
	 * @param string $nameAndId
	 * @return \York\HTML\DomElementAbstract | \York\HTML\ContainableDomElementInterface
	 */
	public function setNameAndId($nameAndId){
		return $this
			->setId($nameAndId)
			->setName($nameAndId);
	}

	/**
	 * (non-PHPdoc)
	 * @see DomElementInterface::getId()
	 */
	public function getId(){
		return $this->get('id');
	}

	/**
	 * (non-PHPdoc)
	 * @see DomElementInterface::getName()
	 */
	public function getName(){
		return $this->get('name');
	}

	/**
	 * renders the label if one is set and the position is before
	 */
	public function displayLabelBefore(){
		if(null !== $this->label && 'before' === $this->label->getPosition()){
			$this->label->display();
		}
	}

	/**
	 * renders the label if one is set and the position is after
	 */
	public function displayLabelAfter(){
		if(null !== $this->label && 'after' === $this->label->getPosition()){
			$this->label->display();
		}
	}

	/**
	 * constructor
	 */
	public final function __construct(){
		parent::__construct();
		$this->init();
	}

	/**
	 * initialises the data object
	 * sets the automatic generated id and name to the element
	 *
	 * @return \York\HTML\DomElementAbstract
	 */
	public function init(){
		$this->addData(\York\HTML\Core::getUniqueIdAndName());
		return $this;
	}
}
