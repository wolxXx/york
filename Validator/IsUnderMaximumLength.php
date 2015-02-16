<?php
namespace York\Validator;

/**
 * validator for checking that the given data is under the maximum length
 *
 * @package \York\Validator
 * @version $version$
 * @author wolxXx
 */
class IsUnderMaximumLength implements \York\Validator\ValidatorInterface
{
    /**
     * @var integer
     */
    protected $maximumLength;

    /**
     * @param integer $maximumLength
     */
    public function __construct($maximumLength)
    {
        $this->maximumLength = $maximumLength;
    }

    /**
     * @inheritdoc
     */
    public function isValid($data)
    {
        try {
            if (false === is_string($data)) {
                throw new \York\Exception\Validator('seems that $data is not a string!');
            }

            if (strlen($data) > $this->maximumLength) {
                throw new \York\Exception\Validator('given string is over the maximum length');
            }
        } catch (\Exception $exeption) {
            throw new \York\Exception\Validator('seems that $data is not a string!');
        }

        return true;
    }
}
