<?php
/**
 * @codeCoverageIgnore
 */
class RouterTest extends \PHPUnit_Framework_TestCase{
	/***
	 * @var \York\Router
	 */
	protected $router;

	/**
	 * @var array
	 */
	protected $path = array();

	public function setup(){
		$this->router = new \York\Router();
		$this->path = array();
	}

	public function testClearForUrl(){
		$input = 'Hallo Welt Das Ist Ein Test';
		$output = 'Hallo-Welt-Das-Ist-Ein-Test';
		$this->assertSame($output, \York\Router::clearForUrl($input));

		$input = 'Hallo Welt Das Ist Ein Test?';
		$output = 'Hallo-Welt-Das-Ist-Ein-Test';
		$this->assertSame($output, \York\Router::clearForUrl($input));
	}

	public function testDefault(){
		$this->router->addRoute('test', array('foo'));
		$url = 'test';

		$this->router->checkRoutes($url, $this->path);
		$this->assertSame($this->path, array('foo'));

		$url = 'test2';
		$this->router->checkRoutes($url, $this->path);
		$this->assertSame($this->path, array('foo'));
	}

	public function testWithCallable(){
		$url = 'foobar';

		$this->router->addRoute($url, function(&$path){
			$path = str_split('foobar', 2);
		});

		$this->router->checkRoutes($url, $this->path);
		$this->assertSame($this->path, array('fo', 'ob', 'ar'));
	}

	public function testWithSimpleString(){
		$url = 'foobar';


		$this->router->addRoute($url, 'asdf');
		$this->router->checkRoutes($url, $this->path);
		$this->assertSame($this->path, array('asdf'));
	}

	public function testWithRegex(){
		$url = 'test/view/%s';
		$url2 = 'test/view/5';

		$this->router->addRoute($url, array('test', 'view', 0));
		$this->router->checkRoutes($url2, $this->path);
		$this->assertEquals($this->path, array('test', 'view', 5));
	}
}
