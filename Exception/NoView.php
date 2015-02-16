<?php
namespace York\Exception;

/**
 * exception for not found views
 *
 * @package York\Exception
 * @version $version$
 * @author wolxXx
 */
class NoView extends \York\Exception\General
{
    /**
     * @inheritdoc
     */
    public function __construct($message)
    {
        parent::__construct($message);

        \York\Dependency\Manager::getSession()->set('type', '404');
        \York\Dependency\Manager::getViewManager()->set('type', '404');
    }
}
