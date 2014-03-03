<?php
/**
 * @codeCoverageIgnore
 */
class PaginatorTest extends \PHPUnit_Framework_TestCase{
	function testDefaultConstructor(){
		$paginator = new \York\View\Paginator();
		$this->assertFalse($paginator->isHidePaginator());
		$this->assertSame(1, $paginator->getPageNumber());
		$this->assertSame(1, $paginator->getPages());
		$this->assertSame('', $paginator->getUrlPrefix());
	}
}