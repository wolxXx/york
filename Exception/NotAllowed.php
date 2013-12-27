<?php
namespace York\Exception;
/**
 * exception for not allowed actions
 *
 * @author wolxXx
 * @version 3.0
 * @package York\Exception
 */
class NotAllowed extends Auth{
	public function __construct(){
		\York\Helper::addSplash(\York\Translator::translate('Diese Seite ist für dich nicht bestimmt!'));
		\York\Helper::redirect('/error/403');
	}
}