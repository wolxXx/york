<?php
/**
 * @codeCoverageIgnore
 */
class IsStringTest extends \PHPUnit_Framework_TestCase{
	public function testDataIsNull(){
		$this->setExpectedException('\York\Exception\Validator');
		$validator = new \York\Validator\IsString();
		$validator->isValid(null);
	}

	public function testDataIsNumeric(){
		$this->setExpectedException('\York\Exception\Validator');
		$validator = new \York\Validator\IsString();
		$validator->isValid(1234);
	}

	public function testDataIsArray(){
		$this->setExpectedException('\York\Exception\Validator');
		$validator = new \York\Validator\IsString();
		$validator->isValid(array('foo' => 'bar'));
	}

	public function testDataIsOk(){
		$validator = new \York\Validator\IsString();
		$this->assertTrue($validator->isValid('hello'));
	}

	public function testDataIsNEmptyString(){
		$validator = new \York\Validator\IsString();
		$this->assertTrue($validator->isValid(''));
	}
}
