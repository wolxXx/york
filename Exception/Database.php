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
		\York\Helper::logerror($message);
	}
}