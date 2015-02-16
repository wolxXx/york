<?php
namespace York\View;

/**
 * Interface LayoutInterface
 *
 * @package \York\View
 * @version $version$
 * @author wolxXx
 */
interface LayoutInterface
{
    /**
     * @return $this
     */
    public function render();

    /**
     * @return string
     */
    public function getContent();

    /**
     * @param string $key
     *
     * @return mixed
     */
    public function get($key);
}
