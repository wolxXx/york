<?php
namespace York\Validator;

/**
 * validator for checking that the given data is not empty
 *
 * @package \York\Validator
 * @version $version$
 * @author wolxXx
 */
class NotEmpty implements \York\Validator\ValidatorInterface
{
    /**
     * @inheritdoc
     */
    public function isValid($data)
    {
        if (true === empty($data)) {
            throw new \York\Exception\Validator('given data is empty');
        }

        return true;
    }
}
