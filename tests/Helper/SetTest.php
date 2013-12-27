<?php
class SetTest extends \PHPUnit_Framework_TestCase{
	public function testMerge(){
		$array1 = array(
			'foo' => 'bar',
			'lol' => 'rofl',
			'true' => 'false'
		);
		$array2 = array(
			'true' => 'and nothing else matters',
			'lol' => 'rofl',
			'42' => '1337'
		);
		$expected = array(
			'foo' => 'bar',
			'lol' => 'rofl',
			'true' => 'and nothing else matters',

			42 => '1337',

		);
		$this->assertSame($expected, \York\Helper\Set::merge($array1, $array2));
	}
}
