<?php
namespace York\Exception;

/**
 * framework exception
 *
 * @package York\Exception
 * @version $version$
 * @author wolxXx
 */
class General extends \Exception
{
    /**
     * @return $this
     */
    public static function Factory()
    {
        return new static();
    }
}
