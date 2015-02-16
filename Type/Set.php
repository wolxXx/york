<?php
namespace York\Type;

/**
 * Class Set
 *
 * @package \York\Type
 * @version $version$
 * @author wolxXx
 */
class Set extends AbstractType
{
    /**
     * @inheritdoc
     */
    protected function validate()
    {
        if (false === is_array($this->value)) {
            throw new \York\Exception\UnexpectedValueForType('expected set or array, got ' . gettype($this->value));
        }
    }
}
