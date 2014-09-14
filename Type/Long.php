<?php
namespace York\Type;
/**
 * Class Long
 *
 * @package York\Type
 * @author wolxXx
 * @version 3.0
 */
class Long extends AbstractType{
	/**
	 * @inheritdoc
	 */
	protected function validate(){
		if(false === is_long($this->value)){
			throw new \York\Exception\UnexpectedValueForType('expected long, got '.gettype($this->value));
		}
	}
}
