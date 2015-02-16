<?php
namespace York\Request\Api;
/**
 * interface for request code class
 *
 * @package \York\Request\Api
 * @version $version$
 * @author wolxXx
 */
interface CodeInterface
{
    /**
     * @param integer $code
     * @return string
     * @throws \York\Exception\General
     */
    public static function getStatusTextForCode($code);
}
