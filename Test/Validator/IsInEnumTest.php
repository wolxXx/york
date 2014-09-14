<?php
/**
 * @codeCoverageIgnore
 */
class IsInEnumTest extends \PHPUnit_Framework_TestCase{
	public function testEnumIsEmpty(){
		$this->setExpectedException('\York\Exception\Validator');
		$validator = new \York\Validator\IsInEnum(array());
		$validator->isValid('foobar');
	}

	public function testDataIsOutOfEnum(){
		$this->setExpectedException('\York\Exception\Validator');
		$validator = new \York\Validator\IsInEnum(array('rofl', 'lol'));
		$validator->isValid('foobar');
	}

	public function testDataIsOk(){
		$validator = new \York\Validator\IsInEnum(array('rofl', 'lol', 'foobar'));
		$this->assertTrue($validator->isValid('foobar'));
	}
}
