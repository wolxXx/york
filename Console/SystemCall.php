<?php
namespace York\Console;

/**
 * system call abstraction
 *
 * @package York\Console
 * @version $version$
 * @author wolxXx
 */
class SystemCall
{
    /**
     * the command
     *
     * @var string
     */
    protected $command;

    /**
     * the arguments
     *
     * @var string[]
     */
    protected $arguments;

    /**
     * the result output
     *
     * @var string[]
     */
    protected $result;

    /**
     * flag for command has run
     *
     * @var boolean
     */
    protected $hasRun = false;

    /**
     * set up
     *
     * @param string    $command
     * @param string[]  $arguments
     */
    public function __construct($command = null, $arguments = array())
    {
        $this->command = $command;
        $this->arguments = $arguments;
    }

    /**
     * factory function
     *
     * @param string $command
     * @param array $arguments
     *
     * @return \York\Console\SystemCall
     */
    public static function Factory($command, $arguments = array())
    {
        return new self($command, $arguments);
    }

    /**
     * run the command
     *
     * @throws \York\Exception\SystemCall
     *
     * @return $this
     */
    public function run()
    {
        if (null === $this->command) {
            throw new \York\Exception\SystemCall('command not set!');
        }

        \York\Dependency\Manager::getLogger()->log($this->getCommand(), \York\Logger\Level::CONSOLE_RUN);

        exec($this->getCommand(true), $resultOutput, $result);
        $this->result = $resultOutput;
        $this->hasRun = true;

        return $this;
    }

    /**
     * set the command
     *
     * @param string $command
     * @return $this
     */
    public function setCommand($command)
    {
        $this->command = $command;

        return $this;
    }

    /**
     * set the arguments
     *
     * @param array $arguments
     * @return $this
     */
    public function setArguments(array $arguments)
    {
        $this->arguments = $arguments;

        return $this;
    }

    /**
     * add argument
     *
     * @param string $argument
     * @return $this
     */
    public function addArgument($argument)
    {
        $this->arguments[] = $argument;

        return $this;
    }

    /**
     * get the output
     *
     * @return \string[]
     * @throws \York\Exception\SystemCall
     */
    public function getOutput()
    {
        if (true !== $this->hasRun) {
            throw new \York\Exception\SystemCall('you must run the call before getting the result!');
        }

        return $this->result;
    }

    /**
     * retrieve the command
     * set $withArguments to true to have the complete command with arguments
     *
     * @param bool $withArguments
     *
     * @throws \York\Exception\SystemCall
     * @return string
     */
    public function getCommand($withArguments = true)
    {
        if (null === $this->command) {
            throw new \York\Exception\SystemCall('command not set!');
        }

        if (false === $withArguments) {
            return $this->command;
        }

        return sprintf('%s %s', $this->command, implode(' ', $this->arguments));
    }
}
