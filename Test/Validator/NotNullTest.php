<?php
/**
 * @codeCoverageIgnore
 */
class NotNullTest extends \PHPUnit_Framework_TestCase{
	public function testDataIsNull(){
		$this->setExpectedException('\York\Exception\Validator');

		$validator = new \York\Validator\NotNull();
		$validator->isValid(null);
	}

	public function testDataIsOk(){
		$validator = new \York\Validator\NotNull();
		$this->assertTrue($validator->isValid(''));
		$this->assertTrue($validator->isValid('1337'));
		$this->assertTrue($validator->isValid(array()));
		$this->assertTrue($validator->isValid(array('42')));
		$this->assertTrue($validator->isValid(1234));
	}
}
