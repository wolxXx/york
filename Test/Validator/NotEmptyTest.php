<?php
/**
 * @codeCoverageIgnore
 */
class NotEmptyTest extends \PHPUnit_Framework_TestCase{
	public function testDataIsNull(){
		$this->setExpectedException('\York\Exception\Validator');

		$validator = new \York\Validator\NotEmpty();
		$validator->isValid(null);
	}

	public function testDataIsEmptyString(){
		$this->setExpectedException('\York\Exception\Validator');

		$validator = new \York\Validator\NotEmpty();
		$validator->isValid('');
	}

	public function testDataIsZero(){
		$this->setExpectedException('\York\Exception\Validator');

		$validator = new \York\Validator\NotEmpty();
		$validator->isValid(0);
	}

	public function testDataIsEmptyArray(){
		$this->setExpectedException('\York\Exception\Validator');

		$validator = new \York\Validator\NotEmpty();
		$validator->isValid(array());
	}

	public function testDataIsOk(){
		$validator = new \York\Validator\NotEmpty();
		$this->assertTrue($validator->isValid('1234'));
		$this->assertTrue($validator->isValid(1234));
		$this->assertTrue($validator->isValid(array(42 => 'the answer')));
	}
}
