<?php
/**
 * @codeCoverageIgnore
 */
class ArrayNotEmptyTest extends \PHPUnit_Framework_TestCase{
	public function testDataIsNotAnArray(){
		$this->setExpectedException('\York\Exception\Validator');
		$validator = new \York\Validator\ArrayNotEmpty();
		$validator->isValid('asdf');
	}

	public function testDataIsNull(){
		$this->setExpectedException('\York\Exception\Validator');
		$validator = new \York\Validator\ArrayNotEmpty();
		$validator->isValid(null);
	}


	public function testDataIsAnEmptyArray(){
		$this->setExpectedException('\York\Exception\Validator');
		$validator = new \York\Validator\ArrayNotEmpty();
		$validator->isValid(array());
	}

	public function testDataIsOk(){
		$validator = new \York\Validator\ArrayNotEmpty();
		$this->assertTrue($validator->isValid(array('foo' => 'bar')));
	}
}
