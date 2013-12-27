<?php
namespace York\HTML\Element;
/**
 * container for form elements
 *
 * @author wolxXx
 * @version 3.0
 * @package \York\HTML\Element
 * @todo $isUploadForm as class member? not sure, dude :)
 */
class Form extends \York\HTML\ContainableDomElementAbstract{
	/**
	 * @param array $data
	 * @return \York\HTML\Element\Form
	 */
	public static function Factory($data = array()){
		return parent::Factory($data);
	}

	/**
	 * flag if the enctype is multipart/form-data
	 *
	 * @var boolean
	 */
	protected $isUploadForm = false;

	/**
	 * (non-PHPdoc)
	 * @see DomElementInterface::getDefaultConf()
	 */
	public static function getDefaultConf(){
		return array(
			'action' => '',
			'method' => 'post',
			'enctype' => null
		);
	}

	/**
	 * setter for the method
	 * only post or get are allowed!
	 *
	 * @param string $method
	 * @return \York\HTML\Element\Form
	 */
	public function setMethod($method){
		$this->set('method', 'post' === $method? 'post' : 'get');
		return $this;
	}

	/**
	 * setter for the action
	 *
	 * @param string $action
	 * @return \York\HTML\Element\Form
	 */
	public function setAction($action){
		$this->set('action', $action);
		return $this;
	}

	/**
	 * setter for upload form flag
	 *
	 * @param boolean $isUploadForm
	 * @return \York\HTML\Element\Form
	 */
	public function setIsUploadForm($isUploadForm = true){
		$this->isUploadForm = true === $isUploadForm;
		return $this;
	}

	/**
	 * (non-PHPdoc)
	 * @see DomElementInterface::display()
	 */
	public function display(){
		$this->displayLabelBefore();

		$conf = $this->getConf();

		if(true === $this->isUploadForm){
			$conf['enctype'] = 'multipart/form-data';
			$conf['method'] = 'post';
		}

		\York\HTML\Core::out(
			\York\HTML\Core::openTag('form', $conf)
		);

		/**
		 * @var \York\HTML\DomElementAbstract $current
		 */
		foreach($this->children as $current){
			$current->display();
		}
		\York\HTML\Core::out(
			\York\HTML\Core::closeTag('form')
		);

		$this->displayLabelAfter();

		return $this;
	}
}