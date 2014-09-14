<?php
/**
 * @codeCoverageIgnore
 */
class IsArrayTest extends \PHPUnit_Framework_TestCase{
	public function testDataIsNull(){
		$validator = new \York\Validator\IsArray();
		$this->setExpectedException('\York\Exception\Validator');
		$validator->isValid(null);
	}

	public function testDataIsString(){
		$validator = new \York\Validator\IsArray();
		$this->setExpectedException('\York\Exception\Validator');
		$validator->isValid('lorl');
	}

	public function testDataIsNumber(){
		$validator = new \York\Validator\IsArray();
		$this->setExpectedException('\York\Exception\Validator');
		$validator->isValid(1337);
	}

	public function testDataIsOk(){
		$validator = new \York\Validator\IsArray();
		$this->assertTrue($validator->isValid(array('42' => 'the answer')));
	}
}

