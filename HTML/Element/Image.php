<?php
namespace York\HTML\Element;

/**
 * a input element
 *
 * @package \York\HTML\Element
 * @version $version$
 * @author wolxXx
 */
class Image extends \York\HTML\DomElementAbstract
{
    /**
     * @param array $data
     *
     * @return \York\HTML\Element\Image
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
            'src' => null,
            'alt' => null
        );
    }

    /**
     * setter for the src
     *
     * @param string $src
     *
     * @return $this
     */
    public function setSrc($src)
    {
        $this->set('src', $src);

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

        \York\HTML\Core::out(
            \York\HTML\Core::openSingleTag('img', $conf),
            \York\HTML\Core::closeSingleTag()
        );

        return $this;
    }
}
