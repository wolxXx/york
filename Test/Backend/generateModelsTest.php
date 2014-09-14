<?php
/**
 * @codeCoverageIgnore
 */
class BackendGenerateModelsTest extends \PHPUnit_Framework_TestCase{
	public function testRunList(){
		$this->markTestSkipped('use if setting flags is possible');
		return;
		$this->expectOutputRegex('/1.6/');
		$this->expectOutputRegex('/generate models/');
		$script = new \York\Backend\Script\ModelGenerator('generate models', '1.6');
		$script->help();
	}

	public function testRunLists(){
		$this->markTestSkipped('use if setting flags is possible');
		return;
		$this->expectOutputRegex('/1.6/');
		$this->expectOutputRegex('/generate models/');
		$script = new \York\Backend\Script\ModelGenerator('generate models', '1.6');
		$script->init();
	}
}
