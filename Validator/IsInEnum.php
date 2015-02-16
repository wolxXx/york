<?php
namespace York\Validator;

/**
 * check if the given data is in the array
 *
 * @package \York\Validator
 * @version $version$
 * @author wolxXx
 */
class IsInEnum implements \York\Validator\ValidatorInterface
{
    /**
     * @var array
     */
    protected $acceptedValues;

    /**
     * @param array $acceptedValues
     *
     * @throws \York\Exception\Validator
     */
    public function __construct(array $acceptedValues)
    {
        if (true === empty($acceptedValues)) {
            throw new \York\Exception\Validator('empty enum found!');
        }

        $this->acceptedValues = $acceptedValues;
    }

    /**
     * @param mixed $data
     *
     * @return boolean
     *
     * @throws \York\Exception\Validator
     */
    public function isValid($data)
    {
        if (false === in_array($data, $this->acceptedValues)) {
            throw new \York\Exception\Validator('given data is not in the accepted values range');
        }

        return true;
    }
}
