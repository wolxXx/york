<?php
namespace York\Validator;
/**
 * validator for checking that the current request contains post data
 *
 * @author wolxXx
 * @version 3.1
 * @package York\Validator
 */
class IsPost implements ValidatorInterface{
	/**
	 * @inheritdoc
	 */
	public function isValid($data){
		if(false === \York\Dependency\Manager::getRequestManager()->isPost()){
			throw new \York\Exception\Validator('not a post request');
		}

		return true;
	}
}
