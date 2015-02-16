<?php
namespace York\Type;

/**
 * Class Integer
 *
 * @package \York\Type
 * @version $version$
 * @author wolxXx
 */
class Integer extends AbstractType
{
    /**
     * @inheritdoc
     */
    protected function validate()
    {
        if (false === is_int($this->value)) {
            throw new \York\Exception\UnexpectedValueForType('expected integer, got ' . gettype($this->value));
        }
    }
}
