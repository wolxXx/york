<?php
namespace Application\Configuration;

/**
 * here you can expand or overwrite the core bootstrap
 * use the hooks (before, after) (run, view) e.g.
 *
 * @package Application\Configuration
 * @version 1.0
 * @author  York Framework
 *
 * @codeCoverageIgnore
 */
class Bootstrap extends \York\Bootstrap
{
    /**
     * @inheritdoc
     * @codeCoverageIgnore
     */
    public function beforeRun()
    {
        //my stuff

        return $this;
    }

    /**
     * @inheritdoc
     * @codeCoverageIgnore
     */
    public function beforeView()
    {
        //my stuff
    }

    /**
     * @inheritdoc
     */
    public function getController()
    {
        // my stuff
        return parent::getController();
    }
}
