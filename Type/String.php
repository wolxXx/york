<?php
namespace York\Type;

/**
 * Class String
 *
 * @package York\Type
 * @author wolxXx
 * @version 3.0
 */
class String extends AbstractType{
	/**
	 * @inheritdoc
	 */
	protected function validate(){
		if(false === is_string($this->value)){
			throw new \York\Exception\UnexpectedValueForType('expected string, got '.gettype($this->value));
		}
	}
}
