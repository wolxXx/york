<?php
class HelperPasswordTest  extends \PHPUnit_Framework_TestCase{
	public function testGenerateNumericPassword(){
		$password = \York\Helper\Password::generateNumericPassword();
		$this->assertSame(4, strlen($password));
		$this->assertTrue(is_numeric($password));

		$password = \York\Helper\Password::generateNumericPassword(10);
		$this->assertSame(10, strlen($password));
		$this->assertTrue(is_numeric($password));
	}

	public function testGeneratePassword(){
		$password = \York\Helper\Password::generatePassword();
		$this->assertSame(9, strlen($password));

		$password = \York\Helper\Password::generatePassword(10);
		$this->assertSame(10, strlen($password));
	}
}
