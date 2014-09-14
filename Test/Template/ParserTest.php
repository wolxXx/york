<?php
/**
 * @codeCoverageIgnore
 */
class TemplateParserTest extends \PHPUnit_Framework_TestCase{
	public function testParseTextWithoutParams(){
		$this->assertSame('asdf', \York\Template\Parser::parseText('asdf', array()));
	}

	public function testParseTextWithParams(){
		$text = <<<TEXT
%%foobar%%
rofl
TEXT;
		$assertion = <<<ASSERTION
lol
rofl
ASSERTION;
		$this->assertSame($assertion, \York\Template\Parser::parseText($text, array('foobar' => 'lol')));
	}

	public function testParseFileWithoutParams(){
		$target = __DIR__.'/fixtures/template1';
		$this->assertSame(file_get_contents($target), \York\Template\Parser::parseFile($target, array()));
	}

	public function testParseFileWithParams(){
		$assertion = <<<ASSERTION
FDJPUNX OK!
pan
rofl

ASSERTION;
		$target = __DIR__.'/fixtures/template1';
		$this->assertSame($assertion, \York\Template\Parser::parseFile($target, array('peter' => 'pan', 'foobar' => 'rofl')));
	}

	public function testFileNotExistent(){
		$this->setExpectedException('\York\Exception\FileSystem');
		\York\Template\Parser::parseFile('hamwaaufgarkeinenfalldigga!!!', array());
	}

}
