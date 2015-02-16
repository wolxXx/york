<?php
namespace York\Validator;

/**
 * validator for checking that the given data is an array
 *
 * @package \York\Validator
 * @version $version$
 * @author wolxXx
 */
class IsArray implements \York\Validator\ValidatorInterface
{
    /**
     * @inheritdoc
     */
    public function isValid($data)
    {
        if (false === is_array($data)) {
            throw new \York\Exception\Validator('given data is not an array!');
        }

        return true;
    }
}
