<?php
class ParserTest extends \PHPUnit_Framework_TestCase{
	public function testParseButFileDoesNotExist(){
		$this->setExpectedException('\York\Exception\FileSystem');
		\York\Template\Parser::parseFile(__DIR__.'/fixtures/hamwanich.txt', array());
	}

	public function testParseTemplate1WithoutParams(){
		$parsed = \York\Template\Parser::parseFile(__DIR__.'/fixtures/template1.txt', array());
		$plain = file_get_contents(__DIR__.'/fixtures/template1.txt');
		$this->assertSame($plain, $parsed);
	}

	public function testParseText(){
		$text = 'lol %%foo%% rofl';
		$expected = 'lol bar rofl';
		$params = array('foo' => 'bar');
		$this->assertSame($expected, \York\Template\Parser::parseText($text, $params));
	}

	public function testParseTemplate1(){
		$params = array(
			'name' => 'young padawan',
			'frameworkthatrocks' => 'york framework!'
		);

		$expected = <<<TEXT
hello {$params['name']}

thank your for using {$params['frameworkthatrocks']}

kind regards,

wolxXx

TEXT;


		$parsed = \York\Template\Parser::parseFile(__DIR__.'/fixtures/template1.txt', $params);
		$this->assertSame($expected, $parsed);

	}
}
