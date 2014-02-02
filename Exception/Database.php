<?php
namespace York\Exception;
/**
 * exception for database errors
 *
 * @author wolxXx
 * @version 3.0
 * @package York\Exception
 */
class Database extends York{
	public function __construct($message){
		/**
		 * @var \York\Logger\Manager $logger
		 */
		$logger = \York\Dependency\Manager::get('logger');
		$logger->log('database error: '.$message, $logger::LEVEL_DATABASE_ERROR);
		parent::__construct($message);
	}
}
