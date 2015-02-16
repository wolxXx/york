<?php
namespace York\Exception;

/**
 * exception for wanting the controller's redirect
 *
 * @package York\Exception
 * @version $version$
 * @author wolxXx
 */
class Redirect extends \York\Exception\General
{
    /**
     * @var null | string
     */
    public $target;

    /**
     * @param null | string $target
     */
    public function __construct($target = null)
    {
        $this->setTarget($target);
    }

    /**
     * @return null | string
     */
    public function getTarget()
    {
        return $this->target;
    }

    /**
     * @param null | string $target
     *
     * @return $this
     */
    public function setTarget($target)
    {
        $this->target = $target;

        return $this;
    }
}
