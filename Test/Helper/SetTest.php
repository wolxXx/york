<?php
class HelperSetTest  extends \PHPUnit_Framework_TestCase{
	public function testSubSet(){
		$input = range(0,10);
		$output = \York\Helper\Set::subSet($input);
		$this->assertSame(range(0,2), $output);

		$input = range(0,10);
		$output = \York\Helper\Set::subSet($input, 3, 1);
		$this->assertSame(range(1,3), $output);
	}

	public function testMerge(){
		$this->assertSame(array(1,2), \York\Helper\Set::merge(array(1), array(2)));
	}

	public function testArraySplit(){
		$this->assertSame(array(), \York\Helper\Set::array_split(array(), 2));
		$this->assertSame(array(array(1), array(2)), \York\Helper\Set::array_split(array(1,2), 2));
		$this->assertSame(array(array(1,2), array(3)), \York\Helper\Set::array_split(array(1,2,3), 2));
		$this->assertSame(array(array(1,2), array(3,4)), \York\Helper\Set::array_split(array(1,2,3,4), 2));
		$this->assertSame(array(array(1), array(2), array(3)), \York\Helper\Set::array_split(array(1,2,3), 3));
	}

	public function testArrayRepeat(){
		$this->assertSame(array(), \York\Helper\Set::array_repeat(array()));
		$this->assertSame(array(1,1), \York\Helper\Set::array_repeat(array(1)));
		$this->assertSame(array(1,1,1), \York\Helper\Set::array_repeat(array(1), 2));
		$this->assertSame(array(1,1,1,1), \York\Helper\Set::array_repeat(array(1), 3));
	}

	public function testDecorate(){
		$this->assertSame(array('x1x'), \York\Helper\Set::decorate(array('1'), 'x'));
		$this->assertSame(array('x1y'), \York\Helper\Set::decorate(array('1'), 'x', 'y'));
		$this->assertSame(array(array('x1y')), \York\Helper\Set::decorate(array(array('1')), 'x', 'y'));
		$this->assertSame(array(array('x1y')), \York\Helper\Set::array_decorate(array(array('1')), 'x', 'y'));
	}

	public function testRemoveValue(){
		$this->assertSame(array(0 =>1, 2 => 3), \York\Helper\Set::removeValue(range(1,3), 2));
		$this->assertSame(array(0 =>1, 1 => 2, 2 => 3), \York\Helper\Set::removeValue(range(1,3), 5));
		$this->assertSame(array(0 =>1, 1 => 2, 2 => 3), \York\Helper\Set::removeValue(range(1,3), '2', true));
		$this->assertSame(array(0 =>1, 2 => 3), \York\Helper\Set::removeValue(range(1,3), 2, true));
	}

	public function testRecursiveDiff(){
		$this->assertSame(array(1), \York\Helper\Set::recursiveDiff(array(1), array(2)));
		$this->assertSame(array(2), \York\Helper\Set::recursiveDiff(array(2), array(1)));
		$this->assertSame(array(), \York\Helper\Set::array_diff_recursive(array(), array()));
		$this->assertSame(array(array(1),array(2),array(3)), \York\Helper\Set::recursiveDiff(array(array(1), array(2), array(3)), array()));
	}

	public function testRecursiveDiffWithArrays(){
		$setOne = array(
			'foo' => array(
				'bar' => 'lol',
				'rofl' => array(
					'a', 's', 'd', 'f' => 'fuckyou'
				)
			),
			'muhaha',
			'counter' => range(0,11)
		);
		$setTwo = array(
			'foo' => array(
				'bar' => 'lol',
				'rofl' => array(
					'a', 's', 'd', 'f' => 'fuckyou'
				)
			),
			'muhaha',
			'counter' => range(11, 0)
		);
		$this->assertSame(array('counter' => range(0,11)), \York\Helper\Set::recursiveDiff($setOne, $setTwo));
	}
}
