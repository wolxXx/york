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
		$this->short = $short;
		$this->long = $long;
		$this->init();
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
	 */
	protected function init(){
		$options = getopt($this->short, array($this->long));
		$this->isEnabled = true === isset($options[$this->short]) || true === isset($options[$this->long]);
	}

	/**
	 * check if the flag is enabled
	 *
	 * @return boolean
	 */
	public function isEnabled(){
		return true === $this->isEnabled;
	}
}
