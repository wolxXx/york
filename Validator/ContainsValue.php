<?php
namespace York\Validator;

/**
 * validator for checking if the data is in the needle
 *
 * @package \York\Validator
 * @version $version$
 * @author wolxXx
 */
class ContainsValue implements \York\Validator\ValidatorInterface
{
    /**
     * @var string
     */
    protected $needle;

    /**
     * @param string $needle
     */
    public function __construct($needle)
    {
        $this->needle = $needle;
    }

    /**
     * @inheritdoc
     */
    public function isValid($data)
    {
        if (false === strstr($data, $this->needle)) {
            throw new \York\Exception\Validator('given data does not contain the needle');
        }

        return true;
    }
}
