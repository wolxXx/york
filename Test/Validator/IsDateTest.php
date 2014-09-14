<?php
/**
 * @codeCoverageIgnore
 */
class IsDateTest extends \PHPUnit_Framework_TestCase{
	public function testDataIsNull(){
		$validator = new \York\Validator\IsDate();
		$this->setExpectedException('\York\Exception\Validator');
		$validator->isValid(null);
	}

	public function testDataIsNotOk(){
		$validator = new \York\Validator\IsDate();
		$this->setExpectedException('\York\Exception\Validator');
		$validator->isValid('12345');
	}

	public function testDataIsOk(){
		$validator = new \York\Validator\IsDate();
		$this->assertTrue($validator->isValid('2000-01-02 03:04:05'));
	}
}
