<?php
/**
 * @codeCoverageIgnore
 */
class IsEmptyTest extends \PHPUnit_Framework_TestCase{
	public function testDataIsNull(){
		$validator = new \York\Validator\IsEmpty();
		$this->assertTrue($validator->isValid(null));
	}

	public function testDataIsEmptyArray(){
		$validator = new \York\Validator\IsEmpty();
		$this->assertTrue($validator->isValid(array()));
	}

	public function testDataIsEmptyString(){
		$validator = new \York\Validator\IsEmpty();
		$this->assertTrue($validator->isValid(''));
	}

	public function testDataIsFilledArray(){
		$validator = new \York\Validator\IsEmpty();
		$this->setExpectedException('\York\Exception\Validator');
		$this->assertTrue($validator->isValid(array(42 => 'the answer')));
	}

	public function testDataIsZero(){
		$validator = new \York\Validator\IsEmpty();
		$this->assertTrue($validator->isValid(0));
	}
}
