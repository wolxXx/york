<?php
/**
 * @codeCoverageIgnore
 */
class IsNumericTest extends \PHPUnit_Framework_TestCase{
	public function testDataIsNull(){
		$this->setExpectedException('\York\Exception\Validator');
		$validator = new \York\Validator\IsNumeric();
		$validator->isValid(null);
	}

	public function testDataIsString(){
		$this->setExpectedException('\York\Exception\Validator');
		$validator = new \York\Validator\IsNumeric();
		$validator->isValid('foobar');
	}

	public function testDataIsArray(){
		$this->setExpectedException('\York\Exception\Validator');
		$validator = new \York\Validator\IsNumeric();
		$validator->isValid(array('42' => 'the answe'));
	}

	public function testDataIsOk(){
		$validator = new \York\Validator\IsNumeric();
		$this->assertTrue($validator->isValid(1337));
	}
}
