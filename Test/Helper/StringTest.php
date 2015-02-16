<?php
class HelperStringTest  extends \PHPUnit_Framework_TestCase{
	public function testCutTextNotLongEnough(){
		$text = 'asdf';
		$this->assertSame($text, \York\Helper\String::cutText($text));
	}

	public function testCutText(){
		$text = 'asdf rofl';
		$this->assertSame('asdf !!!', \York\Helper\String::cutText($text, 4, '!!!'));
		$this->assertSame('asdf ...', \York\Helper\String::cutText($text, 4, null));
		$this->assertSame('as!!!', \York\Helper\String::cutText($text, 5, '!!!', true));
		$this->assertSame('as', \York\Helper\String::cutText($text, 2, '!!!', true));
		$this->assertSame('aa!!!', \York\Helper\String::cutText('aaaaaaaaaaaaaaa', 5, '!!!', true));
		$this->assertSame('aaaaaaaaaaaaaaa', \York\Helper\String::cutText('aaaaaaaaaaaaaaa', 5, '!!!'));
	}

	public function testStartsWith(){
		$this->assertTrue(\York\Helper\String::startsWith('asdfasdf', 'asdf'));
		$this->assertTrue(\York\Helper\String::startsWith('asdfasdf', 'as'));
		$this->assertFalse(\York\Helper\String::startsWith('asdfasdf', 'asdfasdfasdf'));
		$this->assertFalse(\York\Helper\String::startsWith('asdfasdf', 'asfd'));
		$this->assertFalse(\York\Helper\String::startsWith('asdfasdf', 'XX'));
	}

	public function testEndsWith(){
		$this->assertTrue(\York\Helper\String::endsWith('foobar', 'bar'));
		$this->assertTrue(\York\Helper\String::endsWith('foobar', 'r'));
		$this->assertTrue(\York\Helper\String::endsWith('foobar', 'foobar'));
		$this->assertFalse(\York\Helper\String::endsWith('foobar', 'X'));
		$this->assertFalse(\York\Helper\String::endsWith('foobar', 'xfoobar'));
	}

	public function testGetClassNameFromNamespace(){
		$this->assertSame('Foobar', \York\Helper\String::getClassNameFromNamespace('\MyLib\Rofl\Foobar'));
		$this->assertSame('Foobar', \York\Helper\String::getClassNameFromNamespace('Foobar'));
	}

	public function testNormalizeString(){
		$this->assertSame('lol', \York\Helper\String::normalizeString('lol'));
		$this->assertSame('motoerhead inferno', \York\Helper\String::normalizeString('motörhead inferno'));
		$this->assertSame('ae oe ue ss ', \York\Helper\String::normalizeString('ä ö ü ß '));
	}

	public function testCleanString(){
		$this->assertSame('lol', \York\Helper\String::cleanString('lol'));
		$this->assertSame('motoerhead_inferno', \York\Helper\String::cleanString('motörhead inferno'));
		$this->assertSame('ae_oe_ue_ss_', \York\Helper\String::cleanString('ä ö ü ß '));
	}

	public function testRemoveSingleTagFromText(){
		$this->assertSame('asdf asdf', \York\Helper\String::removeSingleTagFromText('asdf <img src="foobar" />asdf<img src="foobar" />', 'img'));
		$this->assertSame('asdf', \York\Helper\String::removeSingleTagFromText('<a href="/">asdf</a>', 'a'));
	}

	public function testremoveTagsFromText(){
		$this->assertSame('asdf asdf', \York\Helper\String::removeTagsFromText('<a href="/">asdf</a> <img src="foobar" />asdf<img src="foobar" />'));
	}

	public function testPascalCaseToUnderscores(){
		$this->assertSame('foo_bar_lol_rofl', \York\Helper\String::pascalCaseToUnderscores('FooBarLolRofl'));
		$this->assertSame('foo', \York\Helper\String::pascalCaseToUnderscores('foo'));
		$this->assertSame('foo', \York\Helper\String::pascalCaseToUnderscores('Foo'));
	}

	public function testUnderscoresToPascalCase(){
		$this->assertSame('FooBarLolRofl', \York\Helper\String::underscoresToPascalCase('foo_bar_lol_rofl'));
		$this->assertSame('Foo', \York\Helper\String::underscoresToPascalCase('foo'));
		$this->assertSame('Foo', \York\Helper\String::underscoresToPascalCase('Foo'));
	}

	public function testAddTailingSlashIfNeeded(){
		$this->assertSame('foo/', \York\Helper\String::addTailingSlashIfNeeded('foo'));
		$this->assertSame('foo/', \York\Helper\String::addTailingSlashIfNeeded('foo/'));

		$this->assertSame('foo/', \York\Helper\String::addTailingSlashIfNeeded(new \York\Type\String('foo')));
		$this->assertSame('foo/', \York\Helper\String::addTailingSlashIfNeeded(new \York\Type\String('foo/')));
	}

	public function testIsMailSyntaxOk(){
		$this->assertFalse(\York\Helper\Net::isMailSyntaxOk('asdf'));
		$this->assertFalse(\York\Helper\Net::isMailSyntaxOk('foo@bar'));
		$this->assertFalse(\York\Helper\Net::isMailSyntaxOk('foo@bar.'));
		$this->assertFalse(\York\Helper\Net::isMailSyntaxOk('@bar.'));
		$this->assertTrue(\York\Helper\Net::isMailSyntaxOk('foo@bar.org'));
	}

	public function testIsURLSyntaxOk(){
		$this->assertTrue(\York\Helper\Net::isURLSyntaxOk('http://www.foo.bar'));
		$this->assertFalse(\York\Helper\Net::isURLSyntaxOk('foo.bar'));
	}

	public function testFloatToDecimal(){
		$this->assertSame('14,95', \York\Helper\String::floatToDecimal('14.95'));
	}
}
