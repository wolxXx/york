<?php
namespace York\View\Splash;

/**
 * a splash item
 *
 * @package \York\View\Splash
 * @version $version$
 * @author wolxXx
 */
class Item implements \York\View\Splash\ItemInterface
{
    /**
     * the displayed text
     *
     * @var string
     */
    protected $text;

    /**
     * create a new splash instance
     *
     * @param string $text
     */
    public function __construct($text = '')
    {
        $this->setText($text);
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
        $this->text = $text;

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function getText()
    {
        return $this->text;
    }
}
