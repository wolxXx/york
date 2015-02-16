<?php
namespace York\ErrorHandler;

/***
 * database exception handler
 *
 * @package York\ErrorHandler
 * @version $version$
 * @author wolxXx
 */
class Database extends ErrorHandlerAbstract
{
    /**
     * @inheritdoc
     */
    public function handle()
    {
        $this->logDefaultMessage(\York\Logger\Level::DATABASE_ERROR);

        return $this;
    }
}
