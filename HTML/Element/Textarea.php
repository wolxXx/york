<?php
namespace York\HTML\Element;
/**
 * a textara element
 *
 * @author wolxXx
 * @version 3.0
 * @package York\HTML\Element
 */
class Textarea extends \York\HTML\DomElementAbstract{
	/**
	 * @param array $data
	 * @return \York\HTML\Element\Textarea
	 */
	public static function Factory($data = array()){
		return parent::Factory($data);
	}

	/**
	 * @inheritdoc
	 */
	public static function getDefaultConf(){
		return array(
			'rows' => '1000',
			'cols' => '1000',
			'text' => ''
		);
	}

	/**
	 * sets the text for the textarea
	 *
	 * @param string $text
	 * @return \York\HTML\Element\Textarea
	 */
	public function setText($text){
		$this->set('text', $text);

		return $this;
	}

	/**
	 * setter for the rows
	 *
	 * @param string $rows
	 * @return \York\HTML\Element\Textarea
	 */
	public function setRows($rows){
		$this->set('rows', $rows);

		return $this;
	}

	/**
	 * setter for the cols
	 *
	 * @param string $cols
	 * @return \York\HTML\Element\Textarea
	 */
	public function setCols($cols){
		$this->set('cols', $cols);

		return $this;
	}

	/**
	 * @inheritdoc
	 */
	public function display(){
		$this->displayLabelBefore();

		$conf = $this->getConf();
		$text = $conf['text'];
		unset($conf['text']);

		/*
		 * jepp, simply echo. had some issues with breaks in first and last line in the textarea
		 * @todo make clean if line breaks issue is fixed
		 */
		echo sprintf('%s%s%s', \York\HTML\Core::openTag('textarea', $conf), $text, \York\HTML\Core::closeTag('textarea'));

		$this->displayLabelAfter();

		return $this;
	}
}
