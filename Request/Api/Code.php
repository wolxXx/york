<?php
namespace York\Request\Api;
/**
 * default api codes
 *
 * @package \York\Request\Api
 * @version $version$
 * @author wolxXx
 */
class Code implements CodeInterface
{
    /**
     * @var integer
     */
    const SUCCESS = 0;

    /**
     * @var integer
     */
    const OK = 200;

    /**
     * @var integer
     */
    const ERROR = 500;

    /***
     * @inheritdoc
     */
    public static function getStatusTextForCode($code)
    {
        switch ($code) {
            case self::OK:
                return 'OK';

            case self::ERROR:
                return 'ERROR';

            default: {
                $reflection = new \ReflectionClass(get_called_class());

                if (true === in_array($code, $reflection->getConstants())) {
                    $flip = array_flip($reflection->getConstants());

                    return $flip[$code];
                }

                throw new \York\Exception\General(sprintf('ApiCode "%s" not found', $code));
            }
        }
    }
}
