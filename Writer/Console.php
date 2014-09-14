<?php
namespace York\Writer;

/**
 * writer to the console
 * is now the same as standard output, but may be useful later!
 *
 * @author wolxXx
 * @version 3.0
 * @package York\Writer
 */
class Console extends Standard{
	/**
	 * @inheritdoc
	 */
	public function write($text){
		return parent::write($text.PHP_EOL);
	}
}
