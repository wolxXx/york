<?php
namespace York\HTML\Element;

/**
 * a headline element
 *
 * @package \York\HTML\Element
 * @version $version$
 * @author wolxXx
 */
class Headline extends \York\HTML\DomElementAbstract
{
    /**
     * @param array $data
     *
     * @return \York\HTML\Element\Headline
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
            'size' => 1,
            'text' => ''
        );
    }

    /**
     * setter for the size
     *
     * @param integer $size
     *
     * @return $this
     */
    public function setSize($size)
    {
        $this->set('size', $size);

        return $this;
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

        $size = $conf['size'];
        unset($conf['size']);

        \York\HTML\Core::out(
            \York\HTML\Core::openTag('h' . $size, $conf),
            $text,
            \York\HTML\Core::closeTag('h' . $size)
        );

        return $this;
    }
}
