<?php
/**
 * @codeCoverageIgnore
 */
class IsPostTest extends \PHPUnit_Framework_TestCase{
	public function testIsNotPost(){
		$this->setExpectedException('\York\Exception\Validator');

		$fakeReqeustManager = $this->getMockBuilder('\York\Request\Manager')
			->disableOriginalConstructor()
			->setMethods(array('isPost'))
			->getMock();
		$fakeReqeustManager->expects($this->any())->method('isPost')->will($this->returnValue(false));

		York\Dependency\Manager::setDependency('requestManager', $fakeReqeustManager);

		$validator = new \York\Validator\IsPost();
		$validator->isValid(null);
	}

	public function testIsPost(){
		$fakeReqeustManager = $this->getMockBuilder('\York\Request\Manager')
			->disableOriginalConstructor()
			->setMethods(array('isPost'))
			->getMock();
		$fakeReqeustManager->expects($this->any())->method('isPost')->will($this->returnValue(true));

		York\Dependency\Manager::setDependency('requestManager', $fakeReqeustManager);

		$validator = new \York\Validator\IsPost();
		$this->assertTrue($validator->isValid(null));
	}
}

