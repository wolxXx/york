<?php
namespace York\HTML\Element;

/**
 * a link element
 *
 * @package \York\HTML\Element
 * @version $version$
 * @author wolxXx
 */
class Link extends \York\HTML\DomElementAbstract
{
    /**
     * @var string
     */
    const SAME_TAB = '_self';

    /***
     * @var string
     */
    const NEW_TAB = '_blank';

    /**
     * @param array $data
     *
     * @return \York\HTML\Element\Link
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
            'href' => '#',
            'target' => self::SAME_TAB,
            'text' => ''
        );
    }

    /***
     * setter for the text
     *
     * @param $text
     *
     * @return $this
     */
    public function setText($text)
    {
        $this->set('text', $text);

        return $this;
    }

    /**
     * setter for the href
     *
     * @param string $href
     *
     * @return $this
     */
    public function setHref($href)
    {
        $this->set('href', $href);

        return $this;
    }

    /**
     * setter for the target
     *
     * @param $target
     *
     * @return $this
     */
    public function setTarget($target)
    {
        $this->set('target', $target);

        return $this;
    }

    /**
     * @inheritdoc
     */
    function setLabel(\York\HTML\Element\Label $label)
    {
        return $this;
    }

    /**
     * @inheritdoc
     */
    function addLabel($label = null, $position = 'before')
    {
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
            \York\HTML\Core::openTag('a', $conf),
            $text,
            \York\HTML\Core::closeTag('a')
        );

        return $this;
    }
}
