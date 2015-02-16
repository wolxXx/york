<?php
class HelperApplicationTest  extends \PHPUnit_Framework_TestCase{
	public function testIsCli(){
		$this->assertTrue(\York\Helper\Application::isCli());
	}

	public function testGetRelativePathInApplication(){
		$this->assertSame('', \York\Helper\Application::getRelativePathInApplication(''));
		$this->assertSame('Foo/Bar/lol.php', \York\Helper\Application::getRelativePathInApplication('Foo/Bar/lol.php'));
		$this->assertSame('Foo/Bar/lol.php', \York\Helper\Application::getRelativePathInApplication(\York\Helper\Application::getDocRoot().'Foo/Bar/lol.php'));
	}

	public function testGrabModeAndVersion(){
		\York\Helper\Application::grabModeAndVersion();
		$this->assertSame('main', \York\Dependency\Manager::get('applicationConfiguration')->get('version'));
		$this->assertSame('dev', \York\Dependency\Manager::get('applicationConfiguration')->get('mode'));
	}

	public function testGrabHostName(){
		$this->assertNull(\York\Dependency\Manager::getApplicationConfiguration()->getSafely('hostname'));
		\York\Helper\Application::grabHostName();
		$this->assertNotNull(\York\Dependency\Manager::getApplicationConfiguration()->getSafely('hostname'));
	}

	public function testGetProjectRoot(){
		$path = __DIR__.'/../../../../';
		$path = realpath($path).DIRECTORY_SEPARATOR;
		$this->assertSame($path, \Application\Helper::getProjectRoot());
	}

	public function testGetDocRoot(){
		$path = __DIR__.'/../../../../docroot';
		$path = realpath($path).DIRECTORY_SEPARATOR;
		$this->assertSame($path, \Application\Helper::getDocRoot());
	}

	public function testGetApplicationRoot(){
		$path = __DIR__.'/../../../../Application';
		$path = realpath($path).DIRECTORY_SEPARATOR;
		$this->assertSame($path, \Application\Helper::getApplicationRoot());
	}

	public function testErrorCodeToString(){
		$this->assertSame('E_ERROR', \York\Helper\Application::errorCodeToString(1));
		$this->assertSame('E_WARNING', \York\Helper\Application::errorCodeToString(2));
		$this->assertSame('E_PARSE', \York\Helper\Application::errorCodeToString(4));
		$this->assertSame('E_NOTICE', \York\Helper\Application::errorCodeToString(8));
		$this->assertSame('E_CORE_ERROR', \York\Helper\Application::errorCodeToString(16));
		$this->assertSame('E_CORE_WARNING', \York\Helper\Application::errorCodeToString(32));
		$this->assertSame('E_COMPILE_ERROR', \York\Helper\Application::errorCodeToString(64));
		$this->assertSame('E_COMPILE_WARNING', \York\Helper\Application::errorCodeToString(128));
		$this->assertSame('E_USER_ERROR', \York\Helper\Application::errorCodeToString(256));
		$this->assertSame('E_USER_WARNING', \York\Helper\Application::errorCodeToString(512));
		$this->assertSame('E_USER_NOTICE', \York\Helper\Application::errorCodeToString(1024));
		$this->assertSame('E_STRICT', \York\Helper\Application::errorCodeToString(2048));
		$this->assertSame('E_RECOVERABLE_ERROR', \York\Helper\Application::errorCodeToString(4096));
		$this->assertSame('E_DEPRECATED', \York\Helper\Application::errorCodeToString(8192));
		$this->assertSame('E_USER_DEPRECATED', \York\Helper\Application::errorCodeToString(16384));
	}
}
