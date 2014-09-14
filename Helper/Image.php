<?php
namespace York\Helper;

/**
 * image helper class
 * @author wolxXx
 * @version 3.0
 * @package York\Helper
 */
class Image{
	/**
	 * @param string $path
	 * @param int $width
	 * @param int $height
	 */
	public static function resizeImage($path, $width = 600, $height = 600){
		$command  = sprintf('mogrify -resize %sx%s %s', $width, $height, $path);
		\York\Console\SystemCall::Factory($command)->run();
	}

	/**
	 * creates a thumbnail for an image
	 * forces be $width x $height pixel
	 * it will be stretched down or up!
	 *
	 * @param string $source
	 * @param string $target
	 * @param integer $width
	 * @param integer $height
	 */
	public static function createThumbnail($source, $target, $width = 200, $height = 200){
		$command = sprintf('convert "%s" -antialias -resize %sx%s! "%s"', $source, $width, $height, $target);
		\York\Console\SystemCall::Factory($command)->run();
	}
}
