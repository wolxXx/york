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
		$this->storage = new \York\Storage\Application();
	}

	public function testSetData(){
		$data = array(
			'foo' => 'bar',
			'bar' => 'foo'
		);
		$this->storage->setData($data);
		$this->assertSame('foo', $this->storage->get('bar'));
		$this->assertSame('bar', $this->storage->get('foo'));
	}

	public function testAddData(){
		$data = array(
			'foo' => 'bar',
			'bar' => 'foo'
		);
		$this->storage->addData($data);
		$this->assertSame('bar', $this->storage->get('foo'));
		$this->storage->set('foo', 'lolrofl');
		$this->storage->addData($data, false);
		$this->assertSame('lolrofl', $this->storage->get('foo'));
	}

	public function testRemoveData(){
		$this->assertNull($this->storage->getSafely('foo'));
		$this->storage->set('foo', 'bar');
		$this->assertSame('bar', $this->storage->get('foo'));
		$this->storage->remove('foo');
		$this->assertNull($this->storage->getSafely('foo'));
	}

	public function testRemoveKey(){
		$this->assertNull($this->storage->getSafely('foo'));
		$this->storage->set('foo', 'bar');
		$this->assertSame('bar', $this->storage->get('foo'));
		$this->storage->removeKey('foo');
		$this->assertNull($this->storage->getSafely('foo'));
	}

	public function testClear(){
		$this->assertNull($this->storage->getSafely('foo'));
		$this->storage->set('foo', 'bar');
		$this->assertSame('bar', $this->storage->get('foo'));
		$this->storage->clear();
		$this->assertNull($this->storage->getSafely('foo'));
	}

	public function testClearData(){
		$this->assertNull($this->storage->getSafely('foo'));
		$this->storage->set('foo', 'bar');
		$this->assertSame('bar', $this->storage->get('foo'));
		$this->storage->clearData();
		$this->assertNull($this->storage->getSafely('foo'));
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
