<?php
namespace York\Validator;
/**
 * check if the data fulfills both validators
 * e.q. is email and contains top level domain..
 *
 * will return true if first validator fails
 *
 * or if it is not empty if must have a pattern, be a number, an email.. whatever..
 *
 * @package York\Validator
 * @version 3.1
 * @author wolxXx
 */
class IfAnd implements ValidatorInterface{
	/**
	 * @var ValidatorInterface
	 */
	protected $validator1;

	/**
	 * @var ValidatorInterface
	 */
	protected $validator2;

	/**
	 * @param ValidatorInterface $validator1
	 * @param ValidatorInterface $validator2
	 */
	public function __construct(ValidatorInterface $validator1, ValidatorInterface $validator2){
		$this->validator1 = $validator1;
		$this->validator2 = $validator2;
	}

	/**
	 * @inheritdoc
	 */
	public function isValid($data){
		try{
			$this->validator1->isValid($data);
		}catch (\York\Exception\Validator $exception){
			return true;
		}

		return $this->validator2->isValid($data);
	}
}
