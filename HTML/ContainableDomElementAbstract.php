<?php
namespace York\HTML;

/**
 * abstract class for providing containable elements
 *
 * @package \York\HTML
 * @version $version$
 * @author wolxXx
 */
abstract class ContainableDomElementAbstract extends \York\HTML\DomElementAbstract implements \York\HTML\ContainableDomElementInterface
{
    /**
     * elements in the grid, textareas, inputs, etc
     *
     * @var \York\HTML\DomElementAbstract[]
     */
    protected $children = array();

    /**
     * @inheritdoc
     */
    public function addChild(DomElementInterface $child)
    {
        $this->children[] = $child;

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function addChildren($children = array())
    {
        foreach ($children as $current) {
            $this->addChild($current);
        }

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function getChildren()
    {
        return $this->children;
    }
}
