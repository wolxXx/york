<?php
namespace York\View\Submenu;

/**
 * the submenu item manager
 *
 * @package \York\View\Submenu
 * @version $version$
 * @author wolxXx
 */
class Manager
{
    /**
     * the one and only instance
     *
     * @var \York\View\Submenu\Manager
     */
    protected static $instance = null;

    /**
     * the submenu item containing array
     *
     * @var array
     */
    protected $items = array();

    /**
     * getter for the instance
     *
     * @return \York\View\Submenu\Manager
     */
    public static function getInstance()
    {
        if (null === self::$instance) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    /**
     * constructor in sense of singleton pattern
     */
    protected final function __construct()
    {
        $this->items = array();
    }

    /**
     * adds a submenuitem to the container
     *
     * @param \York\View\Submenu\Item $item
     *
     * @return $this
     */
    public function addItem(\York\View\Submenu\Item $item)
    {
        $this->items[] = $item;

        return $this;
    }

    /**
     * returns all set submenu items
     *
     * @return array
     */
    public function getAllItems()
    {
        return $this->items;
    }

    /**
     * renders the submenu if it contains some elements
     *
     * @return $this
     */
    public function display()
    {
        if (true === empty($this->items)) {
            return $this;
        }

        $div = \York\HTML\Element\Div::Factory();
        $div->setId('submenu');

        foreach ($this->items as $current) {
            $div->addChild(\York\HTML\Element\Plaintext::Factory(array('text' => $current->getOutput())));
        }

        $div->display();

        return $this;
    }
}
