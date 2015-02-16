<?php
namespace York\Writer;

/**
 * interface for writing something to something
 * like file, html, console
 *
 * @package \York\Writer
 * @version $version$
 * @author wolxXx
 */
interface WriterInterface
{
    /**
     * write something to something
     *
     * @param string $text
     *
     * @return $this
     */
    public function write($text);

    /**
     * write something to something in debug context
     *
     * @param string $text
     *
     * @return $this
     */
    public function debug($text);

    /**
     * write something to something in verbose context
     *
     * @param string $text
     *
     * @return $this
     */
    public function verbose($text);

    /**
     * give an instance
     *
     * @return $this
     */
    public static function Factory();
}
