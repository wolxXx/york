<?php
namespace York\Console;

/**
 * class for cli application parameter
 *
 * @author wolxXx
 * @version 3.0
 * @package York\Console
 */
class Parameter{
	/**
	 * long parameter name like --tableName
	 *
	 * @var string
	 */
	protected $long;

	/**
	 * short parameter name like -t
	 *
	 * @var string
	 */
	protected $short;

	/**
	 * flag for required parameter
	 *
	 * @var boolean
	 */
	protected $isRequired;

	/**
	 * default value
	 *
	 * @var mixed
	 */
	protected $default;

	/**
	 * given value
	 *
	 * @var mixed
	 */
	protected $value;

	/**
	 * set up
	 *
	 * @param string $long
	 * @param string $short
	 * @param boolean $isRequired
	 * @param mixed $default
	 */
	public function __construct($long, $short = '', $isRequired = true, $default = null){
		$this->long = $long;
		$this->short = $short;
		$this->setIsRequired($isRequired);
		$this->setDefault($default);
		$this->init();
	}

	/**
	 * factory function
	 *
	 * @param string $long
	 * @param string $short
	 * @param boolean $isRequired
	 * @param mixed $default
	 * @return Parameter
	 */
	public static function Factory($long, $short = '', $isRequired = true, $default = null){
		return new self($long, $short, $isRequired, $default);
	}

	/**
	 * initialize tout
	 *
	 * @throws \York\Exception\Console
	 */
	public function init(){
		$this->value = $this->default;

		$short = $this->short.':';
		$long = $this->long.':';
		if(false === $this->isRequired()){
			$short .= ':';
			$long .= ':';
		}
		$options = $this->parseArgs($short, $long);

		if(false === isset($options[$this->long]) && false === isset($options[$this->short])){
			if(true == $this->isRequired()){
				throw new \York\Exception\Console(sprintf('required parameter "%s" not set!', $this->long));
			}
			return;
		}

		if(true == isset($options[$this->long])){
			$this->value = $options[$this->long];
		}

		if(true == isset($options[$this->short])){
			$this->value = $options[$this->short];
		}

	}

	/**
	 * @param $short
	 * @param $long
	 *
	 * @return array
	 */
	public function parseArgs($short, $long){
		return getopt($short, array($long));
	}

	/**
	 * getter for the calculated value
	 * @return mixed
	 */
	public function getValue(){
		return $this->value;
	}

	/**
	 * setter for the required flag
	 *
	 * @param boolean $isRequired
	 * @return Parameter
	 */
	protected function setIsRequired($isRequired = false){
		$this->isRequired = true === $isRequired;

		return $this;
	}

	/**
	 * getter for the required flag
	 *
	 * @return boolean
	 */
	public function isRequired(){
		return true === $this->isRequired;
	}

	/**
	 * setter for the default value
	 *
	 * @param mixed $default
	 * @return Parameter
	 */
	protected function setDefault($default = null){
		$this->default = $default;

		return $this;
	}

	/**
	 * getter for the default value
	 *
	 * @return mixed
	 */
	public function getDefault(){
		return $this->default;
	}

	public function getLongOption(){
		return $this->long;
	}

	public function getShortOption(){
		return $this->short;
	}
}
