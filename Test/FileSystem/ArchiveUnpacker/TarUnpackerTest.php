<?php
/**
 * @codeCoverageIgnore
 */
class TarUnpackerTest extends \PHPUnit_Framework_TestCase{
	public function setup(){
		parent::setUp();
	}

	public function testFailingConfiguration(){
		$this->setExpectedException('\Exception');
		new \York\FileSystem\ArchiveUnpacker\Tar('foobar');
	}

	public function testRunFailsTargetNotExists(){
		$this->setExpectedException('\York\Exception\FileSystem');

		$source = new \York\FileSystem\File(\York\Type\String::Factory(__DIR__.'/fixtures/tar/archive.tar')->get());
		$target = new \York\FileSystem\Directory(\York\Type\String::Factory(__DIR__.'/fixtures/tar/testTargetxx'));
		$preserveDirectories = new \York\Type\Boolean(false);

		$configuration =  new \York\FileSystem\ArchiveUnpacker\Configuration($source, $target, $preserveDirectories);

		$unpacker = new \York\FileSystem\ArchiveUnpacker\Tar($configuration);
		$unpacker->unpack();
	}

	public function testRunGetAllFiles(){
		$targetName = \York\Type\String::Factory(__DIR__.'/fixtures/tar/testTarget');
		$directory = new \York\FileSystem\Directory($targetName->get(), true);

		$source = new \York\FileSystem\File(\York\Type\String::Factory(__DIR__.'/fixtures/tar/archive.tar.gz')->get());
		$target = new \York\FileSystem\Directory($targetName->get());
		$preserveDirectories = new \York\Type\Boolean(false);

		$configuration =  new \York\FileSystem\ArchiveUnpacker\Configuration($source, $target, $preserveDirectories);
		$unpacker = new \York\FileSystem\ArchiveUnpacker\Tar($configuration);
		$result = $unpacker->unpack();
		$this->assertSame(true, $result->get());

		$directory->delete();
	}
}
