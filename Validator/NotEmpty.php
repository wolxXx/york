<?php
namespace York\Validator;
/**
 * validator for checking that the given data is not empty
 *
 * @author wolxXx
 * @version 3.0
 * @package York\Validator
 */
class NotEmpty implements ValidatorInterface{
	/**
	 * @inheritdoc
	 */
	public function isValid($data){
		if(true === empty($data)){
			throw new \York\Exception\Validator('given data is empty');
		}

		return true;
	}
}
