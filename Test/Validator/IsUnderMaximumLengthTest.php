<?php
/**
 * @codeCoverageIgnore
 */
class IsUnderMaximumLengthTest extends \PHPUnit_Framework_TestCase{
	public function testDataIsNull(){
		$this->setExpectedException('\York\Exception\Validator');

		$validator = new \York\Validator\IsUnderMaximumLength(5);
		$validator->isValid(null);
	}

	public function testDataIsEmptyArray(){
		$this->setExpectedException('\York\Exception\Validator');

		$validator = new \York\Validator\IsUnderMaximumLength(5);
		$validator->isValid(array());
	}

	public function testDataIsFilledArray(){
		$this->setExpectedException('\York\Exception\Validator');

		$validator = new \York\Validator\IsUnderMaximumLength(5);
		$validator->isValid(array('42' => 'the answer'));
	}

	public function testDataIsShortNumber(){
		$this->setExpectedException('\York\Exception\Validator');

		$validator = new \York\Validator\IsUnderMaximumLength(5);
		$validator->isValid(42);
	}

	public function testDataIsLongtNumber(){
		$this->setExpectedException('\York\Exception\Validator');

		$validator = new \York\Validator\IsUnderMaximumLength(5);
		$validator->isValid(1234567890);
	}

	public function testDataIsOk(){
		$validator = new \York\Validator\IsUnderMaximumLength(5);
		$this->assertTrue($validator->isValid('1337'));
	}

	public function testDataIsTooLong(){
		$this->setExpectedException('\York\Exception\Validator');

		$validator = new \York\Validator\IsUnderMaximumLength(5);
		$validator->isValid('lol1337rofl42foobar');
	}
}
