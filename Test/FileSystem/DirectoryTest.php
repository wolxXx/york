<?php
/**
 * @codeCoverageIgnore
 */
class FileSystemDirectoryTest extends \PHPUnit_Framework_TestCase{
	public function setup(){
		parent::setUp();
	}

	public function testDefault(){
		$directory = new \York\FileSystem\Directory(__DIR__);
		$this->assertSame(__DIR__.'/', $directory->getFullPath());

		$this->assertSame('FileSystem', $directory.'');
		$this->assertSame('FileSystem', $directory->getName());
	}

	public function testDirectoryNotExists(){
		$path = __DIR__.'/fixtures/directoryTestDirectory';
		$this->assertFalse(is_dir($path));
		$this->setExpectedException('\York\Exception\FileSystem');
		new \York\FileSystem\Directory($path, false);
	}

	public function testDirectoryIsNotWritableAfterCreation(){
		$this->setExpectedException('\York\Exception\FileSystem');

		/**
		 * @var \York\FileSystem\Directory | PHPUnit_Framework_MockObject_MockObject $mock
		 */
		$mock = $this->getMockBuilder('\York\FileSystem\Directory')
			->setMethods(array('isWritable', 'exists', 'isReadable'))
			->disableOriginalConstructor()
			->getMock();

		#$mock->expects  ($this->any())->method('create')->will($this->returnValue(true));
		$mock->expects($this->any())->method('exists')->will($this->returnValue(true));
		$mock->expects($this->any())->method('isReadable')->will($this->returnValue(true));
		$mock->expects($this->any())->method('isWritable')->will($this->returnValue(false));

		$mock->init();
	}

	public function testDelete(){
		$path = __DIR__.'/fixtures/directoryTestDirectory';
		$this->assertFalse(is_dir($path));
		$directory = new \York\FileSystem\Directory($path, true);
		$this->assertTrue(is_dir($path));
		$this->assertTrue($directory->delete());
		$this->setExpectedException('\York\Exception\FileSystem');
		new \York\FileSystem\Directory($path, false);
	}

	public function testDeleteFails(){
		/**
		 * @var \York\FileSystem\Directory | PHPUnit_Framework_MockObject_MockObject $mock
		 */
		$mock = $this->getMockBuilder('\York\FileSystem\Directory')
			->setMethods(array('init'))
			->setConstructorArgs(array(__DIR__.'/fixtures/asdf'))
			->disableOriginalConstructor()
			->getMock();

		//$mock->expects  ($this->any())->method('create')->will($this->returnValue(true));
		$mock->expects($this->any())->method('init')->will($this->returnValue(true));

		#$mock->init();
		$this->assertFalse($mock->delete());
	}

	public function testCreationFails(){
		$this->setExpectedException('\York\Exception\FileSystem');

		/**
		 * @var \York\FileSystem\Directory | PHPUnit_Framework_MockObject_MockObject $mock
		 */
		$mock = $this->getMockBuilder('\York\FileSystem\Directory')
			->setMethods(array('exists', 'create'))
			->setConstructorArgs(array(__DIR__.'/fixtures/asdf'))
			->disableOriginalConstructor()
			->getMock();

		$mock->expects  ($this->any())->method('create')->will($this->returnValue(true));
		$mock->expects($this->any())->method('exists')->will($this->returnValue(false));

		$mock->init();
	}

	public function testCreationFailsAgain(){
		$this->setExpectedException('\York\Exception\FileSystem');

		/**
		 * @var \York\FileSystem\Directory | PHPUnit_Framework_MockObject_MockObject $mock
		 */
		$mock = $this->getMockBuilder('\York\FileSystem\Directory')
			->setMethods(array('exists', 'create'))
			->setConstructorArgs(array(__DIR__.'/fixtures/asdf'))
			->disableOriginalConstructor()
			->getMock();

		$mock->expects  ($this->any())->method('create')->will($this->throwException(new \York\Exception\FileSystem()));
		$mock->expects($this->any())->method('exists')->will($this->returnValue(false));

		$mock->init();
	}
}
