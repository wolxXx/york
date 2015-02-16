<?php
namespace York\Writer;

/**
 * writer to the standard output
 *
 * @package \York\Writer
 * @version $version$
 * @author wolxXx
 */
class Standard implements WriterInterface
{
    /**
     * @inheritdoc
     */
    public function write($text)
    {
        echo $text;

        return $this;
    }

    /**
     * @inheritdoc
     */
    public static function Factory()
    {
        return new self();
    }

    /**
     * @inheritdoc
     */
    public function debug($text)
    {
        return $this->write($text);
    }

    /**
     * @inheritdoc
     */
    public function verbose($text)
    {
        return $this->write($text);
    }
}
