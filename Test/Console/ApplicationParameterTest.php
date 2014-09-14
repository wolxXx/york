<?php
/**
 * @codeCoverageIgnore
 */
class YorkConsoleParameter extends \PHPUnit_Framework_TestCase{
	public function setUp(){
		parent::setUp();
	}

	public function testInstantiation(){
		$parameter = \York\Console\Parameter::Factory('foobar', 'f', false, 'rofl');
		$this->assertInstanceOf('\York\Console\Parameter', $parameter);
	}

	public function testGetDefault(){
		$parameter = \York\Console\Parameter::Factory('foobar', 'f', false, 'rofl');
		$this->assertSame('rofl', $parameter->getDefault());
	}

	public function testGetLongOption(){
		$parameter = \York\Console\Parameter::Factory('foobar', 'f', false, 'rofl');
		$this->assertSame('foobar', $parameter->getLongOption());
	}

	public function testGetShortOption(){
		$parameter = \York\Console\Parameter::Factory('foobar', 'f', false, 'rofl');
		$this->assertSame('f', $parameter->getShortOption());
	}

	public function testInitWithLongArg(){
		$parameter = $this
			->getMockBuilder('\York\Console\Parameter')
			->disableOriginalConstructor()
			->setMethods(array(
				'parseArgs'
			))
			->getMock();

		$parameter
			->expects($this->any())
			->method('parseArgs')
			->will($this->returnValue(array('foobar' => 'rofl')));

		/**
		 * @var \York\Console\Parameter $parameter
		 */
		$parameter->__construct('foobar', 'f', false, 'test');
		$parameter->init();
		$this->assertSame('rofl', $parameter->getValue());
	}

	public function testInitWithShortArg(){
		$parameter = $this
			->getMockBuilder('\York\Console\Parameter')
			->disableOriginalConstructor()
			->setMethods(array(
				'parseArgs'
			))
			->getMock();

		$parameter
			->expects($this->any())
			->method('parseArgs')
			->will($this->returnValue(array('f' => 'rofl')));

		/**
		 * @var \York\Console\Parameter $parameter
		 */
		$parameter->__construct('foobar', 'f', false, 'test');
		$parameter->init();
		$this->assertSame('rofl', $parameter->getValue());
	}


	public function testNotRequired(){
		$parameter = \York\Console\Parameter::Factory('foobar', 'f', false, 'dafuq');
		$this->assertSame('dafuq', $parameter->getValue());
	}

	public function testRequired(){
		$this->markTestSkipped();
		$params = array('foobar', 'f');

		$flagMockBuilder = $this->getMockBuilder('\York\Console\Parameter');
		$flagMockBuilder->disableOriginalConstructor();
		$flagMockBuilder->setConstructorArgs($params);
		$flagMockBuilder->setMethods(array('parseArgs'));
		$flagMock = $flagMockBuilder->getMock();
		$flagMock->expects($this->any())->method('parseArgs')->will($this->returnValue(array('foobar' => true)));

		/** @var \York\Console\Parameter $flagMock */

		$this->assertSame(array('foobar' => true), $flagMock->parseArgs('foobar', 'f'));
		$flagMock->init();
		$this->assertSame('foobar', $flagMock->getLongOption());
		$this->assertSame('f', $flagMock->getShortOption());

		$this->assertSame('foobar', \York\Console\Parameter::Factory('foobar')->getLongOption());
	}

	public function testRequiredAndFails(){
		$this->setExpectedException('\York\Exception\Console');
		\York\Console\Parameter::Factory('foobar');
	}
}
