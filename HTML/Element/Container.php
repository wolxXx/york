<?php
namespace York\HTML\Element;

/**
 * alias for div element
 *
 * @package \York\HTML\Element
 * @version $version$
 * @author wolxXx
 */
class Container extends \York\HTML\Element\Div
{
    /**
     * @param array $data
     *
     * @return \York\HTML\Element\Container
     */
    public static function Factory($data = array())
    {
        return parent::Factory($data);
    }
}
