<?php
namespace York\HTML\Element;

/**
 * a checkbox element
 *
 * @package \York\HTML\Element
 * @version $version$
 * @author wolxXx
 */
class Checkbox extends \York\HTML\DomElementAbstract
{
    /**
     * @param array $data
     *
     * @return \York\HTML\Element\Checkbox
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
            'checked' => null,
            'value' => null,
            'type' => 'checkbox',
            'style' => null
        );
    }

    /**
     * setter for is selected
     *
     * @param boolean $isChecked
     *
     * @return $this
     */
    public function setIsChecked($isChecked = true)
    {
        $this->set('checked', true === $isChecked ? 'checked' : null);

        return $this;
    }

    /**
     * shortcut for setIsChecked
     *
     * @param boolean $isChecked
     *
     * @return $this
     */
    public function setChecked($isChecked = true)
    {
        return $this->setIsChecked(true === $isChecked);
    }

    /**
     * setter for the value
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
