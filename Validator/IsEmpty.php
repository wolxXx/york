<?php
namespace York\Validator;

/**
 * validator for checking that the given data is empty
 *
 * @package \York\Validator
 * @version $version$
 * @author wolxXx
 */
class IsEmpty implements \York\Validator\ValidatorInterface
{
    /**
     * @inheritdoc
     */
    public function isValid($data)
    {
        if (false === empty($data)) {
            throw new \York\Exception\Validator('given data is not empty');
        }

        return true;
    }
}
