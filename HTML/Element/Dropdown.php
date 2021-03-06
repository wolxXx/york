<?php
namespace York\HTML\Element;

/**
 * a dropdown element container
 *
 * @package \York\HTML\Element
 * @version $version$
 * @author wolxXx
 */
class Dropdown extends \York\HTML\ContainableDomElementAbstract
{
    /**
     * @param array $data
     * @return \York\HTML\Element\Dropdown
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
        return array();
    }

    /**
     * overwrites the abstract method
     * it only accepts dropdown elements or dropdown groups
     *
     * @param \York\HTML\DomElementInterface | \York\HTML\Element\DropdownElement | \York\HTML\Element\DropdownGroup $child
     *
     * @return $this
     *
     * @throws \York\Exception\HTTMLGenerator
     */
    public function addChild(\York\HTML\DomElementInterface $child)
    {
        if ($child instanceof \York\HTML\Element\DropdownElement || $child instanceof \York\HTML\Element\DropdownGroup) {
            parent::addChild($child);

            return $this;
        }

        throw new \York\Exception\HTTMLGenerator('dropdown container can only contain dropdown elements or groups as children');
    }

    /**
     * @inheritdoc
     */
    public function display()
    {
        $this->displayLabelBefore();

        $conf = $this->getConf();

        \York\HTML\Core::out(
            \York\HTML\Core::openTag('select', $conf)
        );


        /**
         * @var \York\HTML\DomElementInterface $current
         */
        foreach ($this->children as $current) {
            $current->display();
        }

        \York\HTML\Core::out(
            \York\HTML\Core::closeTag('select')
        );


        $this->displayLabelAfter();

        return $this;
    }
}
