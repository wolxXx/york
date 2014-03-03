<?php
class ValidatorTest extends \PHPUnit_Framework_TestCase{
	public function testArrayIsNotEmptyFailsBecauseEmpty(){
		$this->setExpectedException('\York\Exception\Validator');
		$validator = new \York\Validator\ArrayNotEmpty();
		$validator->isValid(array());
	}

	public function testArrayIsNotEmptyFailsBecauseNotArray(){
		$this->setExpectedException('\York\Exception\Validator');
		$validator = new \York\Validator\ArrayNotEmpty();
		$validator->isValid('lol');
	}

	public function testArrayIsNotEmpty(){
		$validator = new \York\Validator\ArrayNotEmpty();
		$this->assertTrue($validator->isValid(array('foo' => 'bar')));
	}



	public function testHasMinimumLengthFails(){
		$this->setExpectedException('\York\Exception\Validator');
		$validator = new \York\Validator\HasMinimumLength(10);
		$validator->isValid('lol');
	}

	public function testHasMinimumLength(){
		$validator = new \York\Validator\HasMinimumLength(2);
		$this->assertTrue($validator->isValid('lol'));
	}



	public function testIsArrayFails(){
		$this->setExpectedException('\York\Exception\Validator');
		$validator = new \York\Validator\IsArray();
		$validator->isValid('lol');
	}

	public function testIsArray(){
		$validator = new \York\Validator\IsArray();
		$this->assertTrue($validator->isValid(array()));
	}



	public function testIsEmailFails(){
		$this->setExpectedException('\York\Exception\Validator');
		$validator = new \York\Validator\IsEmail();
		$validator->isValid('lol');
	}

	public function testIsEmail(){
		$validator = new \York\Validator\IsEmail();
		$this->assertTrue($validator->isValid('devops@wolxXx.de'));
	}


	public function testIsEmptyFails(){
		$this->setExpectedException('\York\Exception\Validator');
		$validator = new \York\Validator\IsEmpty();
		$validator->isValid('lol');
	}

	public function testIsEmpty(){
		$validator = new \York\Validator\IsEmpty();
		$this->assertTrue($validator->isValid(''));
	}

	public function testIsNumericFails(){
		$this->setExpectedException('\York\Exception\Validator');
		$validator = new \York\Validator\IsNumeric();
		$validator->isValid('lol');
	}

	public function testIsNumeric(){
		$validator = new \York\Validator\IsNumeric();
		$this->assertTrue($validator->isValid('123'));
		$this->assertTrue($validator->isValid(123));
	}



	public function testIsStringFails(){
		$this->setExpectedException('\York\Exception\Validator');
		$validator = new \York\Validator\IsString();
		$validator->isValid(array());
		$validator->isValid(1234);
	}

	public function testIsString(){
		$validator = new \York\Validator\IsString();
		$this->assertTrue($validator->isValid('123'));
		$this->assertTrue($validator->isValid(''));
	}




	public function testIsUnderMaximumFails(){
		$this->setExpectedException('\York\Exception\Validator');
		$validator = new \York\Validator\IsUnderMaximumLength(2);
		$validator->isValid('lol');
	}

	public function testIsUnderMaximum(){
		$validator = new \York\Validator\IsUnderMaximumLength(10);
		$this->assertTrue($validator->isValid('lol'));
	}




	public function testNotEmptyFails(){
		$this->setExpectedException('\York\Exception\Validator');
		$validator = new \York\Validator\NotEmpty();
		$validator->isValid('');
	}

	public function testNotEmpty(){
		$validator = new \York\Validator\NotEmpty();
		$this->assertTrue($validator->isValid('asdf'));
	}





	public function testNotNullFails(){
		$this->setExpectedException('\York\Exception\Validator');
		$validator = new \York\Validator\NotNull();
		$validator->isValid(null);
	}

	public function testNotNull(){
		$validator = new \York\Validator\NotNull();
		$this->assertTrue($validator->isValid('asdf'));
	}




	public function testIsPostFails(){
		$this->setExpectedException('\York\Exception\Validator');
		$validator = new \York\Validator\IsPost();
		$validator->isValid(null);
	}

	public function testIsPost(){
		$validator = new \York\Validator\IsPost();
		$_POST['foo'] = 'bar';
		$this->assertTrue($validator->isValid('asdf'));
	}
}
