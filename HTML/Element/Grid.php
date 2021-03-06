<?php
namespace York\HTML\Element;

/**
 * grid element
 * useful if 960gs css framework is used
 *
 * @package \York\HTML\Element
 * @version $version$
 * @author wolxXx
 *
 * @todo clear as function? really?
 */
class Grid extends \York\HTML\ContainableDomElementAbstract
{
    /**
     * @param array $data
     *
     * @return \York\HTML\Element\Grid
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
            'size' => '1',
        );
    }

    /**
     * setter for the grid size
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
     * clears the floating
     *
     * @return $this
     */
    public function clear()
    {
        \York\HTML\Element\Clear::Factory()->display();

        return $this;
    }

    /**
     * @inheritdoc
     *
     * @todo really?! need to do this vernünftich, junge!
     * @todo maybe use yielding ....
     */
    public function display()
    {
        $conf = $this->getConf();
        $size = $conf['size'];

        unset($conf['size']);

        $grid = \York\HTML\Element\Div::Factory($conf);
        $grid->addClass('grid_' . $size);
        $grid->addChildren($this->children);
        $grid->display();

        return $this;
    }
}
