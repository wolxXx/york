<?php
namespace York\HTML\Element;

/**
 * displays a break element
 *
 * @package \York\HTML\Element
 * @version $version$
 * @author wolxXx
 */
class Br extends \York\HTML\DomElementAbstract
{
    /**
     * @param array $data
     *
     * @return \York\HTML\Element\Br
     */
    public static function Factory($data = array())
    {
        return parent::Factory($data);
    }

    /**
     * @inheritdoc
     */
    public function display()
    {
        \York\HTML\Core::out('');
        echo \York\HTML\Core::openSingleTag('br');
        echo \York\HTML\Core::closeSingleTag();
        \York\HTML\Core::out('');

        return $this;
    }
}
