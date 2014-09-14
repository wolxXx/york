<?php
/**
 * @codeCoverageIgnore
 */
class YorkYorkTest extends \PHPUnit_Framework_TestCase{
	public function testGeneral(){
		/**
		 * @vsar \York\York | Framework_MockObject $york
		 */
		$york = $this->getMockBuilder('\York\York')
			->disableOriginalConstructor()
			->setMethods(array(
				'getBootstrap',
				'initAutoloader'
			))->getMock();

		$york
			->expects($this->any())
			->method('getBootstrap')
			->will($this->returnValue(new FakeBootstrap()));

		$york
			->expects($this->any())
			->method('initAutoloader')
			->will($this->returnValue(null));

		$this->assertInstanceOf('\York\York', $york);
		$york->run();
	}
}

class FakeBootstrap{
	public function beforeRun(){}
	public function run(){}
	public function afterRun(){}
	public function beforeView(){}
	public function view(){}
	public function afterView(){}
}
