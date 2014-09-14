<?php
/**
 * @codeCoverageIgnore
 */
class DependencyManagerAndRuleTest extends PHPUnit_Framework_TestCase{
	/**
	 * @var \York\Dependency\Manager | PHPUnit_Framework_MockObject_MockObject
	 */
	protected $manager;

	public function setUp(){
		parent::setUp();

		$managerMock = $this
			->getMockBuilder('\York\Dependency\Manager')
			->disableOriginalConstructor()
			->setMethods(array(
				'getPathToApplicationConfiguration',
				'getPathToDefaultConfiguration'
			))
			->getMock('\York\Dependency\Manager');

		$managerMock
			->expects($this->any())
			->method('getPathToApplicationConfiguration')
			->will($this->returnValue(__DIR__.'/fixtures/application'));

		$managerMock
			->expects($this->any())
			->method('getPathToDefaultConfiguration')
			->will($this->returnValue(__DIR__.'/fixtures/default'));

		$this->manager = $managerMock;
	}

	public function testParseConfigFails(){
		$this->markTestSkipped();
		$managerMock = $this->getMockBuilder('\York\Dependency\Manager')
			->disableOriginalConstructor()
			->setMethods(
				array(
					'getPathToApplicationConfiguration',
					'getPathToDefaultConfiguration'
				)
			)->getMock();
		$managerMock
			->expects($this->any())
			->method('getPathToDefaultConfiguration')
			->will($this->returnValue(__DIR__.'/fixtures/hamwaaufgarkeinenfalldigga'));

		$managerMock
			->expects($this->any())
			->method('getPathToApplicationConfiguration')
			->will($this->returnValue(__DIR__.'/fixtures/hamwaaufgarkeinenfalldigga'));

		\York\Dependency\Manager::get('foobar');
	}

	public function testParseNonExistingConfiguration(){
		$this->markTestSkipped('noch nich...');
		$this->manager->expects($this->any())
			->method('getPathToApplicationConfiguration')
			->will($this->returnValue(__DIR__.'/fixtures/fileDoesNotExist'));

		$this->manager->expects($this->any())
			->method('getPathToDefaultConfiguration')
			->will($this->returnValue(__DIR__.'/fixtures/fileDoesNotExist'));

	}


	/**
	 * @depends testHasNoDependencyConfigured
	 */
	public function testHasNoDependencyInstantiated(){
		$this->markTestSkipped('noch nich...');
	}

	public function testSetGetDependency(){
		$this->markTestSkipped('noch nich...');
		$foobar = new \stdClass();
		$foobar->lol = 'rofl';
		$this->manager->getInstance()->setDependency('foobar', $foobar);

		$this->assertSame($this->manager->getInstance()->get('foobar'), $foobar);
	}
}
