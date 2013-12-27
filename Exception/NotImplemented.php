<?php
namespace York\Exception;
/**
 * Exception for saying sorry, this is not implemented yet.
 *
 * @author wolxXx
 * @version 3.0
 * @package York\Exception
 */
class NotImplemented extends York{
	public function __construct(){
		\York\Helper::logToFile($this->getMessage(), 'todo');
		\York\Helper::addSplash(\York\Translator::translate('Entschuldigung, diese Funktionalität wurde noch nicht umgesetzt!'));
		\York\Helper::historyBack();
	}
}