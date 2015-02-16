<?php
namespace York\Logger;

/**
 * logging manager
 * register for all loggers, log messages enter here and this routes the messages with the levels to the instances
 *
 * @package \York\Logger
 * @version $version$
 * @author wolxXx
 */
class Manager
{
    /**
     * list of listening loggers
     *
     * @var \York\Logger\LoggerInterface[]
     */
    protected $registeredLoggers;

    /**
     * initialize the logger manager
     * get your instance via getInstance
     */
    public function __construct()
    {
        $this->registeredLoggers = array();
    }

    /**
     * add a logger to the list of listening loggers
     *
     * @param \York\Logger\LoggerInterface $logger
     *
     * @return $this
     */
    public function addLogger(\York\Logger\LoggerInterface $logger)
    {
        $this->registeredLoggers[] = $logger;
        return $this;
    }

    /**
     * @param integer $level
     *
     * @return boolean
     */
    public function hasLoggerForLevel($level)
    {
        foreach ($this->registeredLoggers as $logger) {
            if (true === $logger->hasLevel($level)) {
                return true;
            }
        }

        return false;
    }

    /**
     * checks if the given level is allowed
     *
     * @param string $level
     *
     * @return boolean
     */
    public function isAllowedLevel($level)
    {
        $reflection = new \ReflectionClass('\York\Logger\Level');

        return true === in_array($level, $reflection->getConstants());
    }

    /**
     * log a message
     *
     * @param string    $message
     * @param string    $level
     *
     * @return \York\Logger\Manager
     */
    public final function log($message, $level = \York\Logger\Level::ALL)
    {
        foreach ($this->registeredLoggers as $current) {
            if (\York\Logger\Level::ALL === $level || true === in_array($level, $current->getLevels())) {
                $current->log($message);
            }
        }

        return $this;
    }


}
