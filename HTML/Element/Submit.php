<?php
namespace York\HTML\Element;

/**
 * a submit button element
 *
 * @package \York\HTML\Element
 * @version $version$
 * @author wolxXx
 */
class Submit extends \York\HTML\DomElementAbstract
{
    /**
     * @param array $data
     *
     * @return \York\HTML\Element\Submit
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
            'name' => null,
            'value' => \York\Dependency\Manager::getTranslator()->translate('Abschicken'),
            'type' => 'submit'
        );
    }

    /**
     * sets the value of this button
     *
     * @param string $value
     *
     * @return $this
     */
    public function setValue($value)
    {
        $this->set('value', $value);

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
