<?php
class HelperFileSystemTest  extends \PHPUnit_Framework_TestCase{

	protected function getFixturesDirectory(){
		return __DIR__.'/fixtures/';
	}

	protected function getTemporaryDirectory(){
		return __DIR__.'/tmp/';
	}

	public function setUp(){
		if(false === is_dir($this->getTemporaryDirectory())){
			mkdir($this->getTemporaryDirectory());
		}
	}

	public function tearDown(){
		\York\Console\SystemCall::Factory('rm -rf '.$this->getTemporaryDirectory())->run();
	}
	public function testGetTemporaryDirectory(){
		$this->assertTrue(is_dir(\York\Helper\FileSystem::getTemporaryDirectory($this->getTemporaryDirectory())));
	}

	public function testCopy(){
		$source = $this->getFixturesDirectory().'copyTestFile';
		$target = $this->getTemporaryDirectory().'foobar';
		\York\Helper\FileSystem::copy($source, $target);
		$this->assertTrue(file_exists($target));
		$this->assertSame(file_get_contents($target), file_get_contents($source));
	}

	public function testMove(){
		$source = $this->getFixturesDirectory().'copyTestFile';
		$target1 = $this->getTemporaryDirectory().'foobar';
		$target2 = $this->getTemporaryDirectory().'roflcoptor';

		\York\Helper\FileSystem::copy($source, $target1);
		$this->assertTrue(file_exists($target1));
		\York\Helper\FileSystem::move($target1, $target2);
		$this->assertTrue(file_exists($target2));
		$this->assertFalse(file_exists($target1));
	}

	public function testGetFileExtension(){
		$this->assertSame('.asdf', \York\Helper\FileSystem::getFileExtension('asdf'));
		$this->assertSame('.asdf', \York\Helper\FileSystem::getFileExtension('foobar.asdf'));
		$this->assertSame('asdf', \York\Helper\FileSystem::getFileExtension('foobar.asdf', false));
		$this->assertSame('.', \York\Helper\FileSystem::getFileExtension(''));
		$this->assertSame('', \York\Helper\FileSystem::getFileExtension('', false));
		$this->assertSame('.coptor', \York\Helper\FileSystem::getFileExtension('/foo/bar/rofl.coptor'));
		$this->assertSame('coptor', \York\Helper\FileSystem::getFileExtension('/foo/bar/rofl.coptor', false));
	}

	public function testIsImage(){
		$this->assertFalse(\York\Helper\FileSystem::isImage($this->getFixturesDirectory().'copyTestFile'));
		$this->assertTrue(\York\Helper\FileSystem::isImage($this->getFixturesDirectory().'image.jpg'));
		$this->assertTrue(\York\Helper\FileSystem::isImage($this->getFixturesDirectory().'image'));
	}

	public function testGetFileName(){
		$this->assertSame('image.jpg', \York\Helper\FileSystem::getFileName($this->getFixturesDirectory().'image.jpg'));
	}

	public function testGetDirectory(){
		$this->assertSame($this->getFixturesDirectory(), \York\Helper\FileSystem::getDirectory($this->getFixturesDirectory().'image.jpg'));
		$this->assertSame($this->getFixturesDirectory(), \York\Helper\FileSystem::getPath($this->getFixturesDirectory().'image.jpg'));
	}

	public function testGetFileType(){
		$this->assertNull(\York\Helper\FileSystem::getFileType('/hamwanich'));
		$this->assertSame('image/jpeg', \York\Helper\FileSystem::getFileType($this->getFixturesDirectory().'image.jpg'));
	}

	public function testGetFileNameWithoutExtension(){
		$this->assertSame('image', \York\Helper\FileSystem::getFileNameWithoutExtension($this->getFixturesDirectory().'image.jpg'));
	}

	public function testScanDirectory(){
		$this->assertEmpty(\York\Helper\FileSystem::scanDirectory($this->getFixturesDirectory().'scantestdir', false, array('image.jpg')));
		$this->assertEmpty(\York\Helper\FileSystem::scanDirectory($this->getFixturesDirectory().'scantestdir', false, 'image.jpg'));
		$this->assertNotEmpty(\York\Helper\FileSystem::scanDirectory($this->getFixturesDirectory().'scantestdir', false));
		$this->assertNotEmpty(\York\Helper\FileSystem::scanDirectory($this->getFixturesDirectory().'scantestdir', false, null));

		$this->assertNotEmpty(\York\Helper\FileSystem::scanDirectory($this->getFixturesDirectory().'scantestdir', true));

		$results = \York\Helper\FileSystem::scanDirectory($this->getFixturesDirectory().'scantestdir', true);
		$this->assertContains($this->getFixturesDirectory().'scantestdir/foo/bar/asdf.jpg', $results);

		$results = \York\Helper\FileSystem::scanDirectory($this->getFixturesDirectory().'scantestdir', false);
		$this->assertNotEmpty($results);
		$this->assertContains($this->getFixturesDirectory().'scantestdir/image.jpg', $results);

	}

	public function testScanNonExistentDirectory(){
		$this->assertEmpty(\York\Helper\FileSystem::scanDirectory('/hamwanich'));
	}

	public function testFileSize(){
		$this->assertSame('4 B', \York\Helper\FileSystem::fileSize(4));
		$this->assertSame('4 KB', \York\Helper\FileSystem::fileSize(4 * 1024));
		$this->assertSame('4 MB', \York\Helper\FileSystem::fileSize(4 * 1024 * 1024));
		$this->assertSame('4 GB', \York\Helper\FileSystem::fileSize(4 * 1024 * 1024 * 1024));
		$this->assertSame(4398046511104, \York\Helper\FileSystem::fileSize(4 * 1024 * 1024 * 1024 * 1024));
	}
}
