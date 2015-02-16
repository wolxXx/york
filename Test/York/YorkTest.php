<?php
/**
 * @codeCoverageIgnore
 */
class YorkYorkTest extends \PHPUnit_Framework_TestCase{
	public function testGeneral(){
        $this->markTestSkipped('nope');
		$york = $this->getMockBuilder('\York\York')
			->disableOriginalConstructor()
			->setMethods(array(
				'initAutoloader',
                'checkRequirements'
			))->getMock();

        $york
            ->expects($this->any())
            ->method('checkRequirements')
            ->will($this->returnValue(true));

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
