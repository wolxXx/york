<?php
/**
 * @codeCoverageIgnore
 */
class StorageSimpleTest extends \PHPUnit_Framework_TestCase{
	/**
	 * @var \York\Storage\Simple
	 */
	protected $storage;
	public function setup(){
		parent::setup();
		$this->storage = new \York\Storage\Simple();
	}

	public function testGetAll(){
		$this->assertSame(array(), $this->storage->getAll());
		$this->storage->set('foo', 'bar');
		$this->assertSame(array('foo' => 'bar'), $this->storage->getAll());
	}

	public function testHasDataForKey(){
		$this->storage->set('foo', 'bar');
		$this->assertFalse($this->storage->hasDataForKey('bar'));
		$this->assertTrue($this->storage->hasDataForKey('foo'));
	}

	public function testRemove(){
		$this->storage->set('foo', 'bar');
		$this->assertTrue($this->storage->hasDataForKey('foo'));
		$this->storage->removeKey('foo');
		$this->assertFalse($this->storage->hasDataForKey('foo'));
	}
}
