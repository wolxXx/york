<?php
class ManagerTest extends \PHPUnit_Framework_TestCase {
	public function testIntantiation(){
		$manager = \York\Logger\Manager::getInstance();
		$this->assertInstanceOf('\York\Logger\Manager', $manager);
	}

	public function testAddLoggerToManager(){
		$manager = \York\Logger\Manager::getInstance();
		$logger = new \York\Logger\Database('log', \York\Logger\Manager::TYPE_DATABASE, \York\Logger\Manager::LEVEL_ALL);
		$manager->addLogger($logger);
	}

	public function testLoggerLevels(){
		$type = \York\Logger\Manager::TYPE_DATABASE;
		$level = \York\Logger\Manager::LEVEL_ALL;
		$logger = new \York\Logger\Database('log', $type, $level);
		$this->assertContains($type, $logger->getTypes());
		$this->assertSameSize(array($type), $logger->getTypes());
	}
}
 