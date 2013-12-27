<?php
namespace York\Exception;
/**
 * exception for needed auth
 *
 * @author wolxXx
 * @version 3.0
 * @package York\Exception
 */
class AuthRequested extends Auth{
	public function __construct(){
		\York\Stack::getInstance()->set('redirect', \York\Helper::getCurrentURI());
		\York\Helper::redirect('/auth/login');
	}
}