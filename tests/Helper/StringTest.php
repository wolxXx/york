<?php
/**
 * Created by PhpStorm.
 * User: wolxxx
 * Date: 13.01.14
 * Time: 08:29
 */

namespace York\Helper;


class StringTest extends \PHPUnit_Framework_TestCase {
	public function testFloatToDecimal(){
		$this->assertSame('13,37', String::floatToDecimal('13.37'));
	}

	public function isMailSyntaxOkDataProvider(){
		return array(
			array(
				true,
				'devops@wolxXx.de'
			),
			array(
				true,
				'devops@wolxXx.local'
			),
			array(
				false,
				'root@localhost'
			)
		);
	}

	/**
	 * @dataProvider isMailSyntaxOkDataProvider
	 */
	public function testIsMailSyntaxOk($result, $check){
		$this->assertSame($result, String::isMailSyntaxOk($check));
	}

	public function testAddTailingSlashIfNeeded(){
		$this->assertSame('foobar/', String::addTailingSlashIfNeeded('foobar'));
	}

	public function testAddTailingSlashIfNeededAgain(){
		$this->assertSame('foobar/', String::addTailingSlashIfNeeded('foobar/'));
	}

	public function testGetClassNameFromNamespace(){
		$this->assertSame('Foobar', String::getClassNameFromNamespace('\York\Pewpew\Foobar'));
	}

	public function testGetClassNameFromNamespaceFails(){
		$this->assertNotSame('Foobar', String::getClassNameFromNamespace('\York\Pewpew\Bazfoo'));
	}

	public function testStartsWith(){
		$this->assertTrue(String::startsWith('foobar', 'foo'));
	}

	public function testStartsWithFails(){
		$this->assertFalse(String::startsWith('foobar', 'bar'));
	}

	public function testStartsWithFailsAgain(){
		$this->assertFalse(String::startsWith('fo', 'bar'));
	}

	public function testEndsWith(){
		$this->assertTrue(String::endsWith('foobar', 'bar'));
	}

	public function testEndsWithFails(){
		$this->assertFalse(String::endsWith('foobar', 'foo'));
	}

	public function testEndsWithFailsAgain(){
		$this->assertFalse(String::endsWith('ar', 'foo'));
	}

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
