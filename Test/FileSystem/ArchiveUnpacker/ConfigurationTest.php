<?php
/**
 * @codeCoverageIgnore
 */
class ArchiveUnpackerConfigurationTest extends \PHPUnit_Framework_TestCase{
	public function testGetSetPreserveFalse(){
		$source = new \York\FileSystem\File(__FILE__);
		$target = new \York\FileSystem\Directory(__DIR__);
		$preserveDirectories = new \York\Type\Boolean(false);

		$configuration = new \York\FileSystem\ArchiveUnpacker\Configuration($source, $target, $preserveDirectories);

		$this->assertSame($source, $configuration->getSource());
		$this->assertSame($target, $configuration->getTarget());
		$this->assertSame($preserveDirectories, $configuration->getPreserveDirectories());
	}

	public function testGetSetPreserveTrue(){
		$source = new \York\FileSystem\File(__FILE__);
		$target = new \York\FileSystem\Directory(__DIR__);
		$preserveDirectories = new \York\Type\Boolean(true);

		$configuration = new \York\FileSystem\ArchiveUnpacker\Configuration($source, $target, $preserveDirectories);

		$this->assertSame($source, $configuration->getSource());
		$this->assertSame($target, $configuration->getTarget());
		$this->assertSame($preserveDirectories, $configuration->getPreserveDirectories());
	}

	public function testGetSetPreserveDefault(){
		$source = new \York\FileSystem\File(__FILE__);
		$target = new \York\FileSystem\Directory(__DIR__);

		$configuration = new \York\FileSystem\ArchiveUnpacker\Configuration($source, $target, null);

		$this->assertSame($source, $configuration->getSource());
		$this->assertSame($target, $configuration->getTarget());
		$this->assertSame(false, $configuration->getPreserveDirectories()->get());
	}
}
