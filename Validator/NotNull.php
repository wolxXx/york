<?php
namespace York\Validator;

/**
 * validator for checking that the given data is not null
 *
 * @package \York\Validator
 * @version $version$
 * @author wolxXx
 */
class NotNull implements \York\Validator\ValidatorInterface
{
    /**
     * @inheritdoc
     */
    public function isValid($data)
    {
        if (null === $data) {
            throw new \York\Exception\Validator('given data is null');
        }

        return true;
    }
}
