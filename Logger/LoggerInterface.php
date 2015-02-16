<?php
namespace York\Logger;

/**
 * interface for all loggers
 *
 * @package \York\Logger
 * @version $version$
 * @author wolxXx
 */
interface LoggerInterface
{
    /**
     * @return $this
     */
    public static function Factory();

    /**
     * log the message
     *
     * @param $message
     *
     * @return boolean
     *
     * @throws \York\Exception\Logger
     */
    public function log($message);

    /**
     * retrieves the levels of the logger
     *
     * @return integer[]
     */
    public function getLevels();

    /**
     * check if logger has the given level
     *
     * @param integer $level
     *
     * @return boolean
     */
    public function hasLevel($level);

    /**
     * clear all set levels
     *
     * @return $this
     */
    public function clearLevels();

    /**
     * overwrites all levels
     *
     * @param string[] $levels
     *
     * @return $this
     */
    public function setLevels($levels);

    /**
     * adds a level
     *
     * @param string $level
     *
     * @return \York\Logger\LoggerAbstract
     *
     * @throws \York\Exception\LoggerLevelNotAllowed
     */
    public function addLevel($level);

    /**
     * setter for the level
     *
     * @param string $level
     *
     * @return \York\Logger\LoggerAbstract
     */
    public function setLevel($level);

    /**
     * @throws \York\Exception\Validator
     *
     * @return $this
     */
    public function validate();
}
