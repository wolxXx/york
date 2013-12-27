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
		\York\Stack::getInstance()->set('type', '404');
		\York\View\Manager::getInstance()->set('type', '404');
	}
}