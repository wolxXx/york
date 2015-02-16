<?php
namespace York\HTML\Element;

/**
 * a single dropdown element
 *
 * @package \York\HTML\Element
 * @version $version$
 * @author wolxXx
 */
class DropdownElement extends \York\HTML\DomElementAbstract
{
    /**
     * @param array $data
     *
     * @return \York\HTML\Element\DropdownElement
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
            'selected' => null,
            'text' => ''
        );
    }

    /**
     * setter for is selected
     *
     * @param boolean $isSelected
     *
     * @return $this
     */
    public function setIsSelected($isSelected = true)
    {
        $this->set('selected', true === $isSelected ? 'selected' : null);

        return $this;
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
     * @return string
     *
     * @throws \York\Exception\KeyNotFound
     */
    public function getValue()
    {
        return $this->get('value');
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
     * sets value and text
     *
     * @param string $value
     *
     * @return $this
     */
    public function setValueAndText($value)
    {
        return $this
            ->setText($value)
            ->setValue($value);
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
            \York\HTML\Core::openTag('option', $conf),
            $text,
            \York\HTML\Core::closeTag('option')
        );

        return $this;
    }
}
