<?php
/**
 * @codeCoverageIgnore
 */
class IsValueTest extends \PHPUnit_Framework_TestCase{
	public function testDataIsNullNonStrict(){
		$this->setExpectedException('\York\Exception\Validator');

		$validator = new \York\Validator\IsValue('1234', false);
		$validator->isValid(null);
	}

	public function testDataIsNullStrict(){
		$this->setExpectedException('\York\Exception\Validator');

		$validator = new \York\Validator\IsValue('1234', true);
		$validator->isValid(null);
	}

	public function testDataIsNotOkForStrictMode(){
		$this->setExpectedException('\York\Exception\Validator');

		$validator = new \York\Validator\IsValue('1234', true);
		$validator->isValid(1234);
	}

	public function testDataIsOkForStrictMode(){
		$validator = new \York\Validator\IsValue('1234', true);
		$this->assertTrue($validator->isValid('1234'));
	}

	public function testDataIsOk(){
		$validator = new \York\Validator\IsValue(1234, false);
		$this->assertTrue($validator->isValid(1234));
		$this->assertTrue($validator->isValid('1234'));
	}
}
