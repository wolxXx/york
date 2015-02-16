<?php
namespace York\Logger;

/**
 * abstract class for all loggers
 *
 * @package \York\Logger
 * @version $version$
 * @author wolxXx
 */
abstract class LoggerAbstract implements LoggerInterface
{
    /**
     * levels of the logger
     *
     * @var string[]
     */
    protected $levels = array();

    /**
     * make this final an protected
     * any usage must be via factory function
     */
    protected final function __construct()
    {
        $this->init();
    }

    /**
     * @return $this
     */
    protected function init()
    {
        return $this;
    }

    /**
     * @inheritdoc
     */
    public final function log($message)
    {
        $this
            ->validate()
            ->logAction($message)
        ;

        return $this;
    }

    /**
     * @param string $message
     *
     * @return $this
     */
    protected abstract function logAction($message);

    /**
     * @inheritdoc
     */
    public function clearLevels()
    {
        $this->levels = array();

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function setLevels($levels = array())
    {
        $this->clearLevels();

        foreach ($levels as $current) {
            $this->addLevel($current);
        }

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function addLevel($level)
    {
        if (false === \York\Dependency\Manager::getLogger()->isAllowedLevel($level)) {
            throw new \York\Exception\LoggerLevelNotAllowed();
        }

        if (false === in_array($level, $this->levels)) {
            $this->levels[] = $level;
        }

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function setLevel($level)
    {
        return $this
            ->clearLevels()
            ->addLevel($level);
    }

    /**
     * @inheritdoc
     */
    public function getLevels()
    {
        return $this->levels;
    }

    /**
     * @inheritdoc
     */
    public function hasLevel($level)
    {
        return true === in_array($level, $this->levels);
    }
}
