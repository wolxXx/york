<?php
namespace York\Validator;
/**
 * check if data is like expected
 *
 * @package York\Validator
 * @version 3.1
 * @author wolxXx
 */
class IsValue implements ValidatorInterface{
	/**
	 * @var mixed
	 */
	protected $compare;

	/**
	 * @var boolean
	 */
	protected $strict;

	/**
	 * @param mixed $compare
	 * @param boolean $strict
	 */
	public function __construct($compare, $strict = true){
		$this->compare = $compare;
		$this->strict = true === $strict;
	}

	/**
	 * @param mixed $data
	 * @return boolean
	 * @throws \York\Exception\Validator
	 */
	public function isValid($data){
		if(true === $this->strict){
			if($data !== $this->compare){
				throw new \York\Exception\Validator('given data does not match the compare data');
			}

			return true;
		}

		if($data != $this->compare){
			throw new \York\Exception\Validator('given data does not match the compare data');
		}

		return true;
	}
}
