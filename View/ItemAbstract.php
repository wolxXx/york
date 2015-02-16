<?php
namespace York\View;

/**
 * abstract class for view items
 *
 * @package \York\View
 * @version $version$
 * @author wolxXx
 */
abstract class ItemAbstract extends \York\Storage\Application implements \York\View\ItemInterface
{
    /**
     * @var string
     */
    protected $content;

    /**
     * @var \York\Request\Application
     */
    protected $request;

    /**
     * @return $thiss
     */
    public static function Factory()
    {
        return new static();
    }

    /**
     * @inheritdoc
     */
    public function setRequest(\York\Request\Application $request)
    {
        $this->request = $request;

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function getRequest()
    {
        return $this->request;
    }

    /**
     * @inheritdoc
     */
    public function prepare()
    {
        return $this;
    }

    /**
     * @inheritdoc
     */
    public final function getContent()
    {
        return $this->content;
    }

    /**
     * @param string $content
     *
     * @return $this
     */
    public function setContent($content)
    {
        $this->content = $content;

        return $this;
    }

    /**
     * @param string $content
     *
     * @return $this
     */
    public function appendContent($content)
    {
        $this->content .= $content;

        return $this;
    }

    /**
     * @param string $content
     *
     * @return $this
     */
    public function prependContent($content)
    {
        $this->content = $content . $this->content;

        return $this;
    }

    /**
     * @return $this
     */
    public function resetContent()
    {
        $this->setContent('');

        return $this;
    }
}
