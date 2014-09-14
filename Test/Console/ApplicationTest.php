<?php
/**
 * @codeCoverageIgnore
 */
class ConsoleApplicationTest extends \PHPUnit_Framework_TestCase{
	public function setUp(){
		parent::setUp();
	}

	public function testParameter(){
		$params = array('foobar', 'f', false);
		$flagMockBuilder = $this->getMockBuilder('\York\Console\Parameter');
		$flagMockBuilder->setConstructorArgs($params);
		$flagMockBuilder->setMethods(array('parseArgs'));
		$flagMock = $flagMockBuilder->getMock();
		$flagMock->expects($this->any())->method('parseArgs')->will($this->returnValue(array('foobar' => 'lol')));

		/** @var \York\Console\Parameter $flagMock */

		$this->assertSame(array('foobar' => 'lol'), $flagMock->parseArgs('foobar', 'f'));
		$flagMock->init();
		$this->assertSame('foobar', $flagMock->getLongOption());
		$this->assertSame('f', $flagMock->getShortOption());
		$this->assertSame('lol', $flagMock->getValue());

		$this->assertSame('foobar', \York\Console\Flag::Factory('foobar')->getLongOption());

	}

	public function testFlag(){
		$params = array('foobar', 'f');

		$flagMockBuilder = $this->getMockBuilder('\York\Console\Flag');
		$flagMockBuilder->setConstructorArgs($params);
		$flagMockBuilder->setMethods(array('parseArgs'));
		$flagMock = $flagMockBuilder->getMock();
		$flagMock->expects($this->any())->method('parseArgs')->will($this->returnValue(array('foobar' => true)));

		/** @var \York\Console\Flag $flagMock */

		$this->assertSame(array('foobar' => true), $flagMock->parseArgs());
		$flagMock->init();
		$this->assertSame('foobar', $flagMock->getLongOption());
		$this->assertSame('f', $flagMock->getShortOption());

		$this->assertTrue($flagMock->isEnabled());

		$this->assertSame('foobar', \York\Console\Flag::Factory('foobar')->getLongOption());
	}
}
