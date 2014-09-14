<?php
/**
 * @codeCoverageIgnore
 */
class ContainsValueTest extends \PHPUnit_Framework_TestCase{
	public function testDataIsNull(){
		$validator = new \York\Validator\ContainsValue('foobar');
		$this->setExpectedException('\York\Exception\Validator');
		$validator->isValid(null);
	}

	public function testDataIsSomethingElse(){
		$validator = new \York\Validator\ContainsValue('foobar');
		$this->setExpectedException('\York\Exception\Validator');
		$validator->isValid('rofl');
	}

	public function testDataIsOk(){
		$validator = new \York\Validator\ContainsValue('foobar');
		$this->assertTrue($validator->isValid('lorlfoobarolf'));
	}
}
