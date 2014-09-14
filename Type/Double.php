<?php
namespace York\Type;
/**
 * Class Double
 *
 * @package York\Type
 * @author wolxXx
 * @version 3.0
 */
class Double extends AbstractType{
	/**
	 * @inheritdoc
	 */
	protected function validate(){
		if(false === is_double($this->value)){
			throw new \York\Exception\UnexpectedValueForType('expected double, got '.gettype($this->value));
		}
	}
}
