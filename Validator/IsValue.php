<?php
namespace York\Validator;

/**
 * check if data is like expected
 *
 * @package \York\Validator
 * @version $version$
 * @author wolxXx
 */
class IsValue implements \York\Validator\ValidatorInterface
{
    /**
     * @var mixed
     */
    protected $compare;

    /**
     * @var boolean
     */
    protected $strict;

    /**
     * @param mixed     $compare
     * @param boolean   $strict
     */
    public function __construct($compare, $strict = true)
    {
        $this->compare = $compare;
        $this->strict = true === $strict;
    }

    /**
     * @inheritdoc
     */
    public function isValid($data)
    {
        if (true === $this->strict) {
            if ($data !== $this->compare) {
                throw new \York\Exception\Validator('given data does not match the compare data');
            }

            return true;
        }

        if ($data != $this->compare) {
            throw new \York\Exception\Validator('given data does not match the compare data');
        }

        return true;
    }
}
