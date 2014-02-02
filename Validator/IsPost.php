<?php
namespace York\Validator;
use York\Exception\Validator;

/**
 * validator for checking that the current request contains post data
 *
 * @author wolxXx
 * @version 3.0
 * @package York\Validator
 */
class IsPost implements ValidatorInterface{
	/**
	 * @inheritdoc
	 */
	public function isValid($data){
		if(false === \York\Dependency\Manager::get('requestManager')->isPost()){
			throw new Validator('not a post request');
		}

		return true;
	}
}
