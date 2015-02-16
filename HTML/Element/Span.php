<?php
namespace York\HTML\Element;

/**
 * a span element
 *
 * @package \York\HTML\Element
 * @version $version$
 * @author wolxXx
 */
class Span extends \York\HTML\DomElementAbstract
{
    /**
     * @param array $data
     *
     * @return \York\HTML\Element\Span
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
        return array(
            'class' => null,
            'text' => ''
        );
    }

    /**
     * setter for the text
     *
     * @param string $text
     *
     * @return $this
     */
    public function setText($text)
    {
        $this->set('text', $text);

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function display()
    {
        $conf = $this->getConf();
        $text = $conf['text'];

        unset($conf['text']);

        \York\HTML\Core::out(
            \York\HTML\Core::openTag('span', $conf),
            $text,
            \York\HTML\Core::closeTag('span')
        );

        return $this;
    }
}
