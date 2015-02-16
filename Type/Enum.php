<?php
namespace York\Type;

/**
 * Enum Type
 *
 * @package \York\Type
 * @version $version$
 * @author wolxXx
 */
abstract class Enum
{
    /**
     * @var mixed
     */
    protected $value;

    /**
     * @param $value
     *
     * @throws \York\Exception\UnexpectedValueForType
     */
    public function __construct($value)
    {
        $this->value = $value;
        $this->check();
    }

    /**
     * @param mixed $value
     *
     * @return $this
     */
    public static function Factory($value)
    {
        return new static($value);
    }

    /**
     * @throws \York\Exception\UnexpectedValueForType
     */
    protected function check()
    {
        $reflection = new \ReflectionClass(get_called_class());

        throw new \York\Exception\UnexpectedValueForType('value not allowed in enum');
    }

    /**
     * @return array
     */
    public static function getValues()
    {
        $reflection = new \ReflectionClass(get_called_class());

        return array_values($reflection->getConstants());
    }

    /**
     * @return mixed
     */
    public function getValue()
    {
        return $this->value;
    }
}

