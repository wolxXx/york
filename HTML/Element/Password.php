<?php
namespace York\HTML\Element;

/**
 * a password input element
 *
 * @package \York\HTML\Element
 * @version $version$
 * @author wolxXx
 */
class Password extends \York\HTML\DomElementAbstract
{
    /**
     * @param array $data
     *
     * @return \York\HTML\Element\Password
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
            'name' => 'password',
            'type' => 'password'
        );
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
