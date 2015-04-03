<?php
namespace York\HTML\Element;

/**
 * a file element
 *
 * @package \York\HTML\Element
 * @version $version$
 * @author wolxXx
 */
class File extends \York\HTML\DomElementAbstract
{
    /**
     * @param array $data
     *
     * @return \York\HTML\Element\File
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
            'value' => null,
            'type' => 'file',
            'multiple' => 'multiple',
            'style' => null
        );
    }

    /**
     * @param boolean $multiple
     *
     * @return $this
     */
    public function setIsMultiple($multiple = true)
    {
        $multiple = true === $multiple? 'multiple' : false;

        return $this;
    }


    /**
     * @inheritdoc
     */
    public function display()
    {
        $this->displayLabelBefore();

        $conf = $this->getConf();

        \York\HTML\Core::out(
            \York\HTML\Core::openSingleTag('input', $conf),
            \York\HTML\Core::closeSingleTag()
        );

        $this->displayLabelAfter();

        return $this;
    }
}
