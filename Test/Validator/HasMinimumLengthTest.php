<?php
/**
 * @codeCoverageIgnore
 */
class HasMinimumLengthTest extends \PHPUnit_Framework_TestCase{
	public function testDataIsNull(){
		$validator = new \York\Validator\HasMinimumLength(5);
		$this->setExpectedException('\York\Exception\Validator');
		$validator->isValid(null);
	}

	public function testDataIsTooShort(){
		$validator = new \York\Validator\HasMinimumLength(5);
		$this->setExpectedException('\York\Exception\Validator');
		$validator->isValid('four');
	}

	public function testDataIsOk(){
		$validator = new \York\Validator\HasMinimumLength(5);
		$this->assertTrue($validator->isValid('five5'));
	}
}
