<?php
/**
 * @codeCoverageIgnore
 */
class YorkControllerTest extends \PHPUnit_Framework_TestCase{
	public function testInstantiation(){
		$this->assertInstanceOf('\York\Controller', new YorkControllerTestClass());
	}
}

class YorkControllerTestClass extends \York\Controller{

}
