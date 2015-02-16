<?php
namespace York\Validator;

/**
 * validator for checking that the given data is a string
 *
 * @package \York\Validator
 * @version $version$
 * @author wolxXx
 */
class IsString implements \York\Validator\ValidatorInterface
{
    /**
     * @inheritdoc
     */
    public function isValid($data)
    {
        if (false === is_string($data)) {
            throw new \York\Exception\Validator('given data is not a string');
        }

        return true;
    }
}
