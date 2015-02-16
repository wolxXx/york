<?php
class HelperTranslatorTest  extends \PHPUnit_Framework_TestCase{
	public function testTranslateDefault(){
		$this->assertSame('foobar', \York\Helper\Translator::translate('foobar'));
	}

	public function testTranslateWithReplacement(){
		$this->assertSame('foobar asdf', \York\Helper\Translator::translate('foobar %s', 'asdf'));
	}

	public function testTranslateFails(){
		$this->setExpectedException('\York\Exception\Translator');
		$this->assertSame('foobar asdf', \York\Helper\Translator::translate('foobar %s %s', 'asdf'));
	}
}
