<?php
namespace York\Validator;
use York\Exception\Validator;

/**
 * validator for checking that the given data is not null
 *
 * @author wolxXx
 * @version 3.0
 * @package York\Validator
 */
class NotNull implements ValidatorInterface{
	/**
	 * @inheritdoc
	 */
	public function isValid($data){
		if(null === $data){
			throw new Validator('given data is null');
		}

		return true;
	}
}
