<?php
namespace York\HTML\Element;

/**
 * a input element
 *
 * @package \York\HTML\Element
 * @version $version$
 * @author wolxXx
 */
class Text extends \York\HTML\Element\Input
{
    /**
     * @param array $data
     *
     * @return \York\HTML\Element\Text
     */
    public static function Factory($data = array())
    {
        return parent::Factory($data);
    }

    /**
     * @param $text
     *
     * @return $this
     */
    public function setText($text)
    {
        $this->set('text', $text);

        return $this;
    }
}
