<?php
namespace York\Logger;

/**
 * echoes the log messages via stdOut
 *
 * @package \York\Logger
 * @version $version$
 * @author wolxXx
 */
class StandardOut extends LoggerAbstract
{
    /**
     * @return $this
     */
    public static function Factory()
    {
        return new self();
    }

    /**
     * @inheritdoc
     */
    public function logAction($message)
    {
        echo $message . PHP_EOL;

        return $this;
    }

    /**
     * @inheritdoc
     * @todo implement me!
     */
    public function validate()
    {
        return $this;
    }

}
