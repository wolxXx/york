<?php
class ManagerTest extends \PHPUnit_Framework_TestCase{
	/**
	 * @var \York\Dependency\Manager
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

	public function testHasNoDependencyConfigured(){
		$this->assertFalse($this->manager->hasDependencyConfigured('foobar'));
	}

	public function testParseConfigFails(){
		$this->setExpectedException('\York\Exception\Dependency');

		$this->manager->expects($this->any())
			->method('getPathToApplicationConfiguration')
			->will($this->returnValue(__DIR__.'/fixtures/corruptConfiguration'));

		$this->manager->getClearInstance();
	}

	public function testParseNonExistingConfiguration(){
		$this->manager->expects($this->any())
			->method('getPathToApplicationConfiguration')
			->will($this->returnValue(__DIR__.'/fixtures/fileDoesNotExist'));

		$this->manager->expects($this->any())
			->method('getPathToDefaultConfiguration')
			->will($this->returnValue(__DIR__.'/fixtures/fileDoesNotExist'));

		$this->manager->getClearInstance();
		$this->assertSame(array(), $this->manager->getInstance()->getConfiguration());
	}


	/**
	 * @depends testHasNoDependencyConfigured
	 */
	public function testHasNoDependencyInstantiated(){
		$this->assertFalse($this->manager->hasDependencyInstantiated('foobar'));
	}

	public function testSetGetDependency(){
		$foobar = new \stdClass();
		$foobar->lol = 'rofl';
		$this->manager->getInstance()->setDependency('foobar', $foobar);
		$this->assertArrayHasKey('foobar', $this->manager->getInstance()->getConfiguration());
		$this->assertTrue($this->manager->getInstance()->hasDependencyConfigured('foobar'));
		$this->assertTrue($this->manager->getInstance()->hasDependencyInstantiated('foobar'));

		$this->assertSame($this->manager->getInstance()->get('foobar'), $foobar);
	}

	public function testConfiguration(){

	}
}
