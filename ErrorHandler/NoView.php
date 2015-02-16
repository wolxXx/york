<?php
namespace York\ErrorHandler;

/**
 * no view exception handler
 * @package York\ErrorHandler
 * @version $version$
 * @author wolxXx
 */
class NoView extends ErrorHandlerAbstract
{
    /**
     * @inheritdoc
     */
    public function handle()
    {
        $message = sprintf('[%s] %s:%s %s', \York\Helper\Date::getDate(), $this->getException()->getFile(), $this->getException()->getLine(), $this->getException()->getMessage());
        \York\Dependency\Manager::getLogger()->log($message, \York\Logger\Level::ERROR);
        \York\Dependency\Manager::getSession()->set('last_error', $this->getException());

        $this->reRunYork('/error/noView');

        return $this;
    }
}
