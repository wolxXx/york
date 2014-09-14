<?php
/**
 * @codeCoverageIgnore
 */
class StorageApplicationTest extends \PHPUnit_Framework_TestCase{
	/**
	 * @var \York\Storage\Application
	 */
	protected $storage;
	public function setup(){
		parent::setup();
		$this->storage = new \York\Storage\Application();
	}

	public function testSetGet(){
		$this->storage->set('foo', 'bar');
		$this->assertSame('bar', $this->storage->get('foo'));
	}

	public function testGetAll(){
		$this->storage->set('foo', 'bar');
		$this->storage->set('bar', 'foo');

		$this->assertSame(array('foo' => 'bar', 'bar' => 'foo'), $this->storage->getAll());
	}

	public function testHasDataForKey(){
		$this->storage->set('foo', 'bar');
		$this->assertFalse($this->storage->hasDataForKey('bar'));
		$this->assertTrue($this->storage->hasDataForKey('foo'));
	}

	public function testRemove(){
		$this->storage->set('foo', 'bar');
		$this->assertTrue($this->storage->hasDataForKey('foo'));
		$this->storage->removeData('foo');
		$this->assertFalse($this->storage->hasDataForKey('foo'));
	}
}
