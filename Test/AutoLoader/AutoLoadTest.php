<?php
/**
 * @codeCoverageIgnore
 */
class AutoLoadTest extends \PHPUnit_Framework_TestCase{
	public function setUp(){
		parent::setUp();
	}

	public function testIsLoadable(){
		$this->assertTrue(\York\Autoload\Manager::isLoadable('\York\Controller'));
	}

	public function testInstantiation(){
		$instance = new \York\Autoload\Manager();
		$this->assertInstanceOf('\York\Autoload\Manager', $instance);
	}
}
