<?php
/**
 * @codeCoverageIgnore
 */
class SplashManagerTest extends \PHPUnit_Framework_TestCase{
	/**
	 * @var \York\View\Splash\Manager
	 */
	protected $manager;
	public function setup(){
		\York\Dependency\Manager::setDependency('session', new \York\Storage\Simple());
		$this->manager = new \York\View\Splash\Manager();
	}

	public function testAddSplash(){
		$this->manager->addSplash(new \York\View\Splash\Item('foobar'));
		$splashes = $this->manager->getSplashes();
		$this->assertInternalType('array', $splashes);
		$this->assertSame(1, sizeof($splashes));
	}

	public function testAddText(){
		$this->manager->addText('foobar');
		$splashes = $this->manager->getSplashes();
		$this->assertInternalType('array', $splashes);
		$this->assertSame(1, sizeof($splashes));
	}

	public function testAddAndClear(){
		$this->manager->addText('foobar');
		$splashes = $this->manager->getSplashes();
		$this->assertInternalType('array', $splashes);
		$this->assertSame(1, sizeof($splashes));
		$this->manager->clearSplashes();
		$splashes = $this->manager->getSplashes();
		$this->assertInternalType('array', $splashes);
		$this->assertSame(0, sizeof($splashes));
	}

	public function testAppendSplash(){
		$this->manager->addText('foo');
		$this->manager->addText('bar', false);
		$splashes = $this->manager->getSplashes();

		$this->assertInternalType('array', $splashes);

		$this->assertSame(2, sizeof($splashes));
		$this->assertSame('foo', $splashes[1]->getText());
		$this->assertSame('bar', $splashes[0]->getText());
	}
}
