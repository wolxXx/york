<?php
namespace York\Writer;

/**
 * writer to the console
 * is now the same as standard output, but may be useful later!
 *
 * @package \York\Writer
 * @version $version$
 * @author wolxXx
 */
class Console extends Standard
{
    /**
     * @inheritdoc
     */
    public function write($text)
    {
        return parent::write($text . PHP_EOL);
    }
}
