<?php
namespace York\View;

/**
 * interface for view items
 *
 * @package \York\View
 * @version $version$
 * @author wolxXx
 */
interface ItemInterface
{
    /**
     * @param \York\Request\Application $request
     *
     * @return $this
     */
    public function setRequest(\York\Request\Application $request);

    /**
     * @return \York\Request\Application
     */
    public function getRequest();

    /**
     * @return $this
     */
    public function prepare();

    /**
     * @return $this
     */
    public function render();

    /**
     * @return string
     */
    public function getContent();
}
