<?php
namespace York\Exception;
/**
 * exception for needed auth
 *
 * @author wolxXx
 * @version 3.0
 * @package York\Exception
 */
class AuthRequested extends \York\Exception\Auth{
	public function __construct(){
		\York\Dependency\Manager::get('session')->set('redirect', \York\Helper\Net::getCurrentURI());
		\York\Helper\Application::redirect('/auth/login');
	}
}
