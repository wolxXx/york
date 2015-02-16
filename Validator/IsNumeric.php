<?php
namespace York\Validator;

/**
 * validator for checking that the given data is numeric
 *
 * @package \York\Validator
 * @version $version$
 * @author wolxXx
 */
class IsNumeric implements \York\Validator\ValidatorInterface
{
    /**
     * @inheritdoc
     */
    public function isValid($data)
    {
        if (false === is_numeric($data)) {
            throw new \York\Exception\Validator('given data is not numeric');
        }

        return true;
    }
}
