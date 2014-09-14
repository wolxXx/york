<?php
namespace York\Type;
/**
 * Class AbstractType
 *
 * @package York\Type
 * @author wolxXx
 * @version 3.0
 */
abstract class AbstractType {
	/**
	 * @var mixed
	 */
	protected $value;

	/**
	 * @param $value
	 * @return AbstractType
	 */
	public static function Factory($value){
		return new static($value);
	}

	/**
	 * @param mixed $value
	 */
	public function __construct($value){
		$this->set($value);
	}

	/**
	 * @param $value
	 * @return boolean
	 */
	public function equals($value){
		return $this->get() === $value;
	}

	/**
	 * @throws \York\Exception\General
	 */
	abstract protected function validate();

	/**
	 * @return mixed
	 */
	public function get(){
		return $this->value;
	}

	/**
	 * @param mixed $value
	 * @return $this
	 */
	public function set($value){
		$this->value = $value;
		$this->validate();

		return $this;
	}

	/**
	 * @return string
	 */
	public function __toString(){
		return $this->get();
	}
}
