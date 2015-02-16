<?php
namespace York\Type;

/**
 * Class Boolean
 *
 * @package \York\Type
 * @version $version$
 * @author wolxXx
 */
class Boolean extends AbstractType
{
    /**
     * @inheritdoc
     */
    protected function validate()
    {
        if (false === is_bool($this->value)) {
            throw new \York\Exception\UnexpectedValueForType('expected boolean, got ' . gettype($this->value));
        }
    }
}
