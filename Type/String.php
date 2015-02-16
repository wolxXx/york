<?php
namespace York\Type;

/**
 * Class String
 *
 * @package \York\Type
 * @version $version$
 * @author wolxXx
 */
class String extends AbstractType
{
    /**
     * @inheritdoc
     */
    protected function validate()
    {
        if (false === is_string($this->value)) {
            throw new \York\Exception\UnexpectedValueForType('expected string, got ' . gettype($this->value));
        }
    }
}
