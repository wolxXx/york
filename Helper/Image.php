<?php
namespace York\Helper;

/**
 * image helper class
 *
 * @package York\Helper
 * @version $version$
 * @author wolxXx
 */
class Image
{
    /**
     * @param string    $path
     * @param integer   $width
     * @param integer   $height
     */
    public static function resizeImage($path, $width = 800, $height = 800)
    {
        $command = sprintf('mogrify -resize %sx%s "%s"', $width, $height, $path);
        \York\Console\SystemCall::Factory($command)->run();
    }

    /**
     * creates a thumbnail for an image
     * forces be $width x $height pixel
     * it will be stretched down or up!
     *
     * @param string    $source
     * @param string    $target
     * @param integer   $width
     * @param integer   $height
     */
    public static function createThumbnail($source, $target, $width = 200, $height = 200)
    {
        $command = sprintf('convert "%s" -antialias -resize %sx%s! "%s"', $source, $width, $height, $target);
        \York\Console\SystemCall::Factory($command)->run();
    }
}
