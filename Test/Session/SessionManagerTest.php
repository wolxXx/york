<?php
/**
 * @codeCoverageIgnore
 */
class SessionManagerTest extends \PHPUnit_Framework_TestCase{
	/**
	 * @var \York\Session\Manager
	 */
	protected $manager;

	public function setup(){
		$_SESSION = array();
		$this->manager = \York\Session\Manager::getInstance();
	}


	public function testGetInstance(){
		$this->assertInstanceOf('\York\Session\Manager', $this->manager);
	}

	public function testReadWrite(){
		$this->assertNull($this->manager->read('foobar'));
		$this->manager->write('foobar', 'dafuq');
		$this->assertSame('dafuq', $this->manager->read('foobar'));
	}
}
