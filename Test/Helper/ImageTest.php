<?php
class HelperImageTest  extends \PHPUnit_Framework_TestCase{
	protected function getFixturesDirectory(){
		return __DIR__.'/fixtures/';
	}

	public function setup(){
		\York\Helper\FileSystem::copy($this->getFixturesDirectory().'original.jpg', $this->getFixturesDirectory().'image.jpg');
	}

	public function tearDown(){
		\York\FileSystem\File::Factory($this->getFixturesDirectory().'thumbnail.jpg', true)->delete();
	}

	public function testResizeImage(){
		$path = $this->getFixturesDirectory().'image.jpg';
		$info = getimagesize($path);
		$this->assertGreaterThan(600, $info[0]);
		\York\Helper\Image::resizeImage($path);
		$info = getimagesize($path);
		$this->assertLessThanOrEqual(600, $info[0]);
	}

	public function testResizeImageWithNonDefaults(){
		$path = $this->getFixturesDirectory().'image.jpg';
		$info = getimagesize($path);
		$this->assertGreaterThan(600, $info[0]);
		\York\Helper\Image::resizeImage($path, 700);
		$info = getimagesize($path);
		$this->assertLessThanOrEqual(700, $info[0]);
	}

	public function testCreateThumbnail(){
		$path = $this->getFixturesDirectory().'image.jpg';
		$target = $this->getFixturesDirectory().'thumbnail.jpg';
		$this->assertTrue(file_exists($path));
		$this->assertFalse(file_exists($target));
		\York\Helper\Image::createThumbnail($path, $target);
		$this->assertTrue(file_exists($path));
		$this->assertTrue(file_exists($target));
		$info = getimagesize($target);
		$this->assertSame(200, $info[0]);
		$this->assertSame(200, $info[1]);
	}
}
