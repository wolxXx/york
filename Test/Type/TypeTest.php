<?php
/**
 * @codeCoverageIgnore
 */
class TypeTest extends \PHPUnit_Framework_TestCase{
	protected function expectException(){
		$this->setExpectedException('\York\Exception\UnexpectedValueForType');
	}

	public function testBool(){
		$bool = new \York\Type\Bool(false);
		$this->assertTrue($bool->equals(false));
		$this->assertFalse($bool->equals(true));
		$this->assertSame(false, $bool->get());
	}

	public function testBoolean(){
		$bool = new \York\Type\Boolean(false);
		$this->assertFalse($bool->get());
		$this->assertSame(false, $bool->get());
	}

	public function testBooleanValidationFails(){
		$this->expectException();
		new \York\Type\Boolean('asdf');
	}

	public function testBoolValidationFails(){
		$this->expectException();
		new \York\Type\Bool('asdf');
	}

	public function testDouble(){
		$value = 3214242424242434424.3;
		$double = new \York\Type\Double($value);
		$this->assertSame($value, $double->get());
	}

	public function testDoubleFails(){
		$this->expectException();
		new \York\Type\Double(3);
	}

	public function testInteger(){
		$value = 1234;
		$integer = new \York\Type\Integer($value);
		$this->assertSame($value, $integer->get());
	}

	public function testIntegerFails(){
		$this->expectException();
		new \York\Type\Integer(true);
	}

	public function testLong(){
		$value = 1378;
		$long = new \York\Type\Long($value);
		$this->assertSame($value, $long->get());
	}

	public function testLongFails(){
		$this->expectException();
		new \York\Type\Long(array());
	}

	public function testObject(){
		$value = new \York\Validator\ArrayNotEmpty();
		$object = new \York\Type\Object($value);
		$this->assertSame($value, $object->get());
	}

	public function testObjectFails(){
		$this->expectException();
		new \York\Type\Object('12345');
	}

	public function testSet(){
		$value = range(0,10);
		$set = new \York\Type\Set($value);
		$this->assertSame($value, $set->get());
	}

	public function testSetFails(){
		$this->expectException();
		new \York\Type\Set(true);
	}

	public function testString(){
		$value = 'foobar';
		$string = new \York\Type\String($value);
		$this->assertSame($value, $string->get());
	}

	public function testStringFails(){
		$this->expectException();
		new \York\Type\String(1337);
	}
}
