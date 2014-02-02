<?php
/**
 * Created by PhpStorm.
 * User: wolxxx
 * Date: 13.01.14
 * Time: 08:29
 */

namespace York\Helper;


class StringTest extends \PHPUnit_Framework_TestCase {
	public function testUnderscoresToPascalCase(){
		$string = 'foobar_lol';
		$this->assertSame('FoobarLol', \York\Helper\String::underscoresToPascalCase($string));

	}

	public function testPascalCaseToUnderscores(){
		$string = 'GoogleSucks';
		$this->assertSame('google_sucks', \York\Helper\String::pascalCaseToUnderscores($string));

		$string = 'Google';
		$this->assertSame('google', \York\Helper\String::pascalCaseToUnderscores($string));
	}

	public function testIsUrlSyntaxNotOk(){
		$this->assertFalse(\York\Helper\String::isURLSyntaxOk('asd'));
	}

	public function testIsUrlSyntaxOk(){
		$this->assertTrue(\York\Helper\String::isURLSyntaxOk('http://wolxxx.de'));
	}
	public function testNormalizeString(){
		$string = 'Hällö! wWü gehts?!';
		$this->assertSame('haelloe! wWue gehts?!', \York\Helper\String::normalizeString($string));
	}

	public function testCleanString(){
		$string = 'Hällö! wWü gehts?!';
		$this->assertSame('Haelloe!_wWue_gehts?!', \York\Helper\String::cleanString($string));
	}

	public function testRemoveHtmlTags(){
		$text = '<a href="test.html">huhu</a> test';
		$this->assertSame('huhu test', \York\Helper\String::removeTagsFromText($text));

		$text = '<h1>huhu</h1> test';
		$this->assertSame('huhu test', \York\Helper\String::removeTagsFromText($text));
	}

	public function testRemoveLinkTagFromText(){
		$text = '<a href="test.html">huhu</a> test';
		$this->assertSame('huhu test', \York\Helper\String::removeSingleTagFromText($text, 'a'));

		$text = '<h1>huhu</h1> test';
		$this->assertSame($text, \York\Helper\String::removeSingleTagFromText($text, 'a'));
	}
}
