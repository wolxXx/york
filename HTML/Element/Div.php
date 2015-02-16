<?php
namespace York\HTML\Element;

/**
 * a div element
 *
 * @package \York\HTML\Element
 * @version $version$
 * @author wolxXx
 */
class Div extends \York\HTML\ContainableDomElementAbstract
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

    /**
     * @inheritdoc
     */
    public static function getDefaultConf()
    {
        return array();
    }

    /**
     * @inheritdoc
     */
    public function display()
    {
        $conf = $this->getConf();

        \York\HTML\Core::out(\York\HTML\Core::openTag('div', $conf));

        foreach ($this->children as $current) {
            $current->display();
        }

        \York\HTML\Core::out(\York\HTML\Core::closeTag('div'));

        return $this;
    }
}
