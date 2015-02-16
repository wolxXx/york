<?php
/**
 * @codeCoverageIgnore
 */
class YorkDatabseCacheTest extends \PHPUnit_Framework_TestCase{
	/**
	 * @var \York\Database\Cache
	 */
	protected $cache;

	public function setup(){
		$this->cache = new \York\Database\Cache();
	}

	public function testInstantiation(){
		$this->assertInstanceOf('\York\Database\Cache', $this->cache);
	}

	public function testHasNoData(){
		$this->assertNull($this->cache->get('fooobar', 1234));
	}

	public function testGetSameModel(){
		$model = new YorkDatabseCacheTestFakeModel('foo', 1234);
		$this->cache->set($model);
		$cached = $this->cache->get(get_class($model), $model->getId());
		$this->assertNotNull($cached);
		$this->assertSame($model->getId(), $cached->getId());
		$this->assertSame($model, $cached);
		$model->setId(666);
		$cached = $this->cache->get(get_class($model), $model->getId());
		$this->assertNull($cached);
	}

	public function testResetAllCache(){
		$model = new YorkDatabseCacheTestFakeModel('foo', 1234);
		$this->cache->set($model);
		$cached = $this->cache->get(get_class($model), $model->getId());
		$this->assertNotNull($cached);
		$this->assertSame($model->getId(), $cached->getId());
		$this->assertSame($model, $cached);
		$this->cache->resetAll();
		$cached = $this->cache->get(get_class($model), $model->getId());
		$this->assertNull($cached);
	}

	public function testResetSectionCache(){
		$model = new YorkDatabseCacheTestFakeModel('foo', 1234);
		$this->cache->set($model);
		$cached = $this->cache->get(get_class($model), $model->getId());
		$this->assertNotNull($cached);
		$this->assertSame($model->getId(), $cached->getId());
		$this->assertSame($model, $cached);
		$this->cache->resetForClass(get_class($model));
		$cached = $this->cache->get(get_class($model), $model->getId());
		$this->assertNull($cached);
	}

	public function testGetSetMultiple(){
		$objects = array(
			new YorkDatabseCacheTestFakeModel('foo', 1234),
			new YorkDatabseCacheTestFakeModel('foo', 1337),
			new YorkDatabseCacheTestFakeModel('foo', 3141)
		);
		$this->cache->addMultiple($objects);
		foreach($objects as $current){
			$cached = $this->cache->get(get_class($current), $current->getId());
			$this->assertNotNull($cached);
			$this->assertSame($current->getId(), $cached->getId());
			$this->assertSame($current, $cached);
		}
	}

	public function testRemove(){
		$objects = array(
			new YorkDatabseCacheTestFakeModel('foo', 1234),
			new YorkDatabseCacheTestFakeModel('foo', 1337),
			new YorkDatabseCacheTestFakeModel('foo', 3141)
		);
		$this->cache->addMultiple($objects);
		$this->cache->remove($objects[1]);
		$cached = $this->cache->get('YorkDatabseCacheTestFakeModel', 1337);
		$this->assertSame(1, sizeof($this->cache->getAll()));
		$this->assertNull($cached);
	}
}

class YorkDatabseCacheTestFakeModel extends \York\Database\Model\Item{
	protected $id;

	/**
	 * @return \York\Database\Model\Manager
	 */
	function getManager(){
		return null;
	}
}
