<?php
/**
 * @codeCoverageIgnore
 */
class IfAndTest extends \PHPUnit_Framework_TestCase{
	public function testFirstFails(){
		$validator = new \York\Validator\IfAnd(
			new \York\Validator\IsEmail(),
			new \York\Validator\ContainsValue('wolxxx.de')
		);
		$this->assertTrue($validator->isValid('1234'));
	}

	public function testSecondFails(){
		$validator = new \York\Validator\IfAnd(
			new \York\Validator\IsEmail(),
			new \York\Validator\ContainsValue('wolxxx.de')
		);
		$this->setExpectedException('\York\Exception\Validator');
		$validator->isValid('peter@google.com');
	}

	public function testDataIsOk(){
		$validator = new \York\Validator\IfAnd(
			new \York\Validator\IsEmail(),
			new \York\Validator\ContainsValue('wolxXx.de')
		);
		$this->assertTrue($validator->isValid('wolxXx@wolxXx.de'));
	}


}
