<?php
namespace York\Exception;
/**
 * exception for not found views
 *
 * @author wolxXx
 * @version 3.0
 * @package York\Exception
 */
class NoView extends York{
	public function __construct(){
		\York\Dependency\Manager::get('session')->set('type', '404');
		\York\Dependency\Manager::get('viewManager')->set('type', '404');
	}
}
