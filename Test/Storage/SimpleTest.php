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
		$this->storage = new \York\Storage\Simple();
	}

	public function testGet(){
		$this->setExpectedException('\York\Exception\KeyNotFound');
		$this->storage->get('hamwanich');
	}

	public function testGetSafely(){
		$this->assertNull($this->storage->getSafely('hamwanich'));
		$this->storage->set('hamwanich', 'hamwadoch');
		$this->assertSame('hamwadoch', $this->storage->getSafely('hamwanich'));
	}

	public function testRemoveKey(){
		$this->storage->set('foo', 'bar');
		$this->assertSame('bar', $this->storage->getSafely('foo'));
		$this->storage->remove('foo');
		$this->assertSame(null, $this->storage->getSafely('foo'));
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
