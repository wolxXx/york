<?php
namespace York\Type;
/**
 * Class Object
 *
 * @package York\Type
 * @author wolxXx
 * @version 3.0
 */
class Object extends AbstractType{
	/**
	 * @inheritdoc
	 */
	protected function validate(){
		if(false === is_object($this->value)){
			throw new \York\Exception\UnexpectedValueForType('expected object, got '.gettype($this->value));
		}
	}
}
