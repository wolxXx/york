<?php
namespace York\ErrorHandler;

/**
 * general exception handler
 *
 * @package York\ErrorHandler
 * @version $version$
 * @author wolxXx
 */
class General extends ErrorHandlerAbstract
{
    /**
     * @inheritdoc
     */
    public function handle()
    {
        $message = sprintf('[%s] %s:%s %s', \York\Helper\Date::getDate(), $this->getException()->getFile(), $this->getException()->getLine(), $this->getException()->getMessage());
        \York\Dependency\Manager::getLogger()->log($message, \York\Logger\Level::DATABASE_ERROR);

        $this->reRunYork();

        return $this;
    }
}
