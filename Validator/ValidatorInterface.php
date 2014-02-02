<?php
namespace York\Validator;

/**
 * validator interface
 *
 * @author wolxXx
 * @version 3.0
 * @package York\Validator
 */
interface ValidatorInterface{
	/**
	 * @param mixed $data
	 * @return boolean
	 * @throws \York\Exception\Validator
	 */
	public function isValid($data);
}
