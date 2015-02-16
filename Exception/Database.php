<?php
namespace York\Exception;

/**
 * exception for database errors
 *
 * @package York\Exception
 * @version $version$
 * @author wolxXx
 */
class Database extends \York\Exception\General
{
    /**
     * @inheritdoc
     */
    public function __construct($message)
    {
        \York\Dependency\Manager::getLogger()->log('database error: ' . $message, \York\Logger\Level::DATABASE_ERROR);

        parent::__construct($message);
    }
}
