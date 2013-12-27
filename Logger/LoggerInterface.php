<?php
namespace York\Logger;
/**
 * interface for all loggers
 *
 * @author wolxXx
 * @version 3.0
 * @package York\Logger
 */
interface LoggerInterface{
	/**
	 * log the message
	 *
	 * @param $message
	 * @return boolean
	 * @throws \York\Exception\Logger
	 */
	public function log($message);

	/**
	 * retrieves the levels of the logger
	 *
	 * @return integer[]
	 */
	public function getLevels();
}
