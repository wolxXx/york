<?php
namespace York\HTML\Element;

/**
 * it clears open grid divs
 *
 * @package \York\HTML\Element
 * @version $version$
 * @author wolxXx
 */
class Clear extends \York\HTML\DomElementAbstract
{
    /**
     * @param array $data
     *
     * @return \York\HTML\Element\Clear
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
        \York\HTML\Core::out(
            \York\HTML\Core::openTag('div', array(
                'class' => 'clear'
            )),
            \York\HTML\Core::closeTag('div')
        );
        return $this;
    }
}
