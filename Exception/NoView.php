<?php
namespace York\Exception;
/**
 * exception for not found views
 *
 * @author wolxXx
 * @version 3.0
 * @package York\Exception
 */
class NoView extends \York\Exception\General{
	public function __construct(){
		\York\Dependency\Manager::getSession()->set('type', '404');
		\York\Dependency\Manager::getViewManager()->set('type', '404');
	}
}
