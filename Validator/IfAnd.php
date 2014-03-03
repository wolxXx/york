<?php
namespace York\Validator;


class IfAnd implements ValidatorInterface{

	protected $validator1;
	protected $validator2;

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
