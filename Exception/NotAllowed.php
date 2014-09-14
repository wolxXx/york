<?php
namespace York\Exception;
/**
 * exception for not allowed actions
 *
 * @author wolxXx
 * @version 3.0
 * @package York\Exception
 */
class NotAllowed extends \York\Exception\Auth{
	public function __construct(){
		\York\Dependency\Manager::get('splashManager')->addText(\York\Helper\Translator::translate('Diese Seite ist für dich nicht bestimmt!'));
		$redirect = new \York\Redirect('/error/403');
		$redirect->redirect();
	}
}
