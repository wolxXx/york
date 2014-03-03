<?php
namespace York\Writer;

/**
 * interface for writing something to something
 * like file, html, console
 *
 * @author wolxXx
 * @version 3.0
 * @package York\Writer
 */
interface WriterInterface {
	/**
	 * write something to something
	 *
	 * @param string $text
	 * @return WriterInterface
	 */
	public function write($text);

	/**
	 * write something to something in debug context
	 *
	 * @param string $text
	 * @return WriterInterface
	 */
	public function debug($text);

	/**
	 * write something to something in verbose context
	 *
	 * @param string $text
	 * @return WriterInterface
	 */
	public function verbose($text);

	/**
	 * give an instance
	 *
	 * @return WriterInterface
	 */
	public static function Factory();
}
