<?php
namespace York\Console;

/**
 * class for representing flags
 *
 * @author wolxXx
 * @version 3.0
 * @package York\Console
 */
class Flag {
	/**
	 * short representation like -h
	 *
	 * @var string
	 */
	protected $short;

	/**
	 * ling representation like --help
	 *
	 * @var string
	 */
	protected $long;

	/**
	 * checker if this flag is enabled
	 *
	 * @var boolean
	 */
	protected $isEnabled = false;

	/**
	 * setup
	 *
	 * @param string $long
	 * @param string $short
	 */
	public function __construct($long, $short = ''){
		$this
			->setShortOption($short)
			->setLongOption($long)
			->init();
	}

	/**
	 * factory function
	 *
	 * @param string $long
	 * @param string $short
	 * @return Flag
	 */
	public static function Factory($long, $short = ''){
		return new self($long, $short);
	}

	/**
	 * check the getopt result if the flag is enabled
	 * @return $this
	 */
	public function init(){
		$options = $this->parseArgs();
		$this->isEnabled = true === isset($options[$this->short]) || true === isset($options[$this->long]);

		return $this;
	}

	/**
	 * parse the options
	 *
	 * @return array
	 */
	public function parseArgs(){
		return getopt($this->short, array($this->long));
	}

	/**
	 * check if the flag is enabled
	 *
	 * @return boolean
	 */
	public function isEnabled(){
		return true === $this->isEnabled;
	}

	/**
	 * @return string
	 */
	public function getLongOption(){
		return $this->long;
	}

	/**
	 * @return string
	 */
	public function getShortOption(){
		return $this->short;
	}

	/**
	 * @param string $long
	 * @return $this
	 */
	public function setLongOption($long){
		$this->long = $long;

		return $this->init();
	}

	/**
	 * @param string $short
	 * @return $this
	 */
	public function setShortOption($short){
		$this->short = $short;

		return $this->init();
	}
}
