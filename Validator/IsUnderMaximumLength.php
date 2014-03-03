<?php
namespace York\Validator;
use York\Exception\Validator;

/**
 * validator for checking that the given data is under the maximum length
 *
 * @author wolxXx
 * @version 3.0
 * @package York\Validator
 */
class IsUnderMaximumLength implements ValidatorInterface{
	/**
	 * @var integer
	 */
	protected $maximumLength;

	/**
	 * @param integer $minimumLength
	 */
	public function __construct($maximumLength){
		$this->maximumLength = $maximumLength;
	}

	/**
	 * @param mixed $data
	 * @return null
	 * @throws \York\Exception\Validator
	 */
	public function isValid($data){
		if(strlen($data) > $this->maximumLength){
			throw new Validator('given string is over the maximum length');
		}

		return true;
	}
}
