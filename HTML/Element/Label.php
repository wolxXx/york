<?php
namespace York\HTML\Element;
/**
 * a label element
*
* @author wolxXx
* @version 3.0
* @package York\HTML\Element
 *
 * @todo position as class member sucks..
*/
class Label extends \York\HTML\DomElementAbstract{
	/**
	 * @param array $data
	 * @return \York\HTML\Element\Label
	 */
	public static function Factory($data = array()){
		return parent::Factory($data);
	}

	/**
	 * position of the label, can be before or after
	 *
	 * @var string
	 */
	protected $position = 'before';

	/**
	 * @inheritdoc
	 */
	public static function getDefaultConf(){
		return array(
			'for' => 'text',
			'text' => 'foo!',
		);
	}

	/**
	 * setter for the position of the element
	 * can be before or after
	 *
	 * @param string $position
	 * @return \York\HTML\Element\Label
	 */
	public function setPosition($position = null){
		$this->position = 'before' === $position? 'before' : 'after';

		return $this;
	}

	/**
	 * returns the wanted position
	 *
	 * @return string
	 */
	public function getPosition(){
		return $this->position;
	}

	/**
	 * @inheritdoc
	 */
	function setLabel(\York\HTML\Element\Label $label){
		return $this;
	}

	/**
	 * @inheritdoc
	 */
	function addLabel($label = null, $position = 'before'){
		return $this;
	}

	/**
	 * setter for the for property
	 *
	 * @param string $for
	 * @return \York\HTML\Element\Label
	 */
	public function setFor($for){
		$this->set('for', $for);

		return $this;
	}

	/**
	 * setter for the text property
	 *
	 * @param string $text
	 * @return \York\HTML\Element\Label
	 */
	public function setText($text){
		$this->set('text', $text);

		return $this;
	}

	/**
	 * getter for the label text
	 *
	 * @return string
	 */
	public function getText(){
		return $this->get('text');
	}

	/**
	 * @inheritdoc
	 */
	public function display(){
		$conf = \York\Helper\Set::merge($this->getDefaultConf(), $this->getData());
		$text = $conf['text'];
		unset($conf['text']);
		\York\HTML\Core::out(
			\York\HTML\Core::openTag('label', $conf),
			$text,
			\York\HTML\Core::closeTag('label')
		);

		return $this;
	}
}
