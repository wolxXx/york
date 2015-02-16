<?php
namespace York\Validator;

/**
 * validator for checking that the given data is a date
 * date format must be 'Y-m-d H:i:s'
 *
 * @package \York\Validator
 * @version $version$
 * @author wolxXx
 */
class IsDate implements \York\Validator\ValidatorInterface
{
    /**
     * @inheritdoc
     */
    public function isValid($data)
    {
        try {
            $isTheSame = $data === \York\Helper\Date::getDateTime(\York\Helper\Date::dateToTimestamp($data))->format(\York\Helper\Date::$format);

            if (false === $isTheSame) {
                throw new \Exception();
            }
        } catch (\Exception $exception) {
            throw new \York\Exception\Validator();
        }

        return true;
    }
}
