<?
/**
 * @codeCoverageIgnore
 */
class FooConfig extends \York\Configuration{}
/**
 * @codeCoverageIgnore
 */
class CoreConfigTest extends  PHPUnit_Framework_TestCase{
	public function testUsual(){
		$config = new \Application\Configuration\Host();
		$config->configureApplication();
		$config->configureHost();
		$config->checkConfig();
	}

	/**
	 * @expectedException ApocalypseException
	 */
	public function testConfigMissingSomething(){
		$config = new HostConfig();
		Stack::getInstance()->unsetKey('db_pass');
		$config->checkConfig();
	}

	/**
	 * @expectedException ApocalypseException
	 */
	public function testNoAppConfigClassException(){
		Stack::getInstance()->clear();
		$foo = new FooConfig();
		$foo->configureApplication();
	}

	/**
	 * @expectedException ApocalypseException
	 */
	public function testNoHostConfigClassException(){
		Stack::getInstance()->clear();
		$this->assertEmpty(Stack::getInstance()->getAll());
		$foo = new FooConfig();
		$foo->configureHost();
	}
}
