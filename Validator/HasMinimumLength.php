<?php
namespace York\Validator;

/**
 * validator for checking that the given data has a minimum length
 *
 * @package \York\Validator
 * @version $version$
 * @author wolxXx
 */
class HasMinimumLength implements \York\Validator\ValidatorInterface
{
    /**
     * @var integer
     */
    protected $minimumLength;

    /**
     * @param integer $minimumLength
     */
    public function __construct($minimumLength)
    {
        $this->minimumLength = $minimumLength;
    }

    /**
     * @inheritdoc
     */
    public function isValid($data)
    {
        if (strlen($data) < $this->minimumLength) {
            throw new \York\Exception\Validator('given string has not the minimum length');
        }

        return true;
    }
}
