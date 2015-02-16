<?php
namespace York\ErrorHandler;

/**
 * abstract class for ErrorHandlers
 *
 * @package York\ErrorHandler
 * @version $version$
 * @author wolxXx
 */
abstract class ErrorHandlerAbstract implements ErrorHandlerInterface
{
    /**
     * @var \Exception
     */
    protected $exception;

    /**
     * @inheritdoc
     */
    public function __construct()
    {
    }

    /**
     * @inheritdoc
     */
    public static function Factory(\Exception $exception)
    {
        $instance = new static();
        $instance->setException($exception);

        return $instance;
    }

    /**
     * @inheritdoc
     */
    public function setException(\Exception $exception)
    {
        $this->exception = $exception;

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function getException()
    {
        return $this->exception;
    }

    /**
     * @return string
     */
    protected function getDefaultMessage()
    {
        $sprintfArgs = array(
            '[%s] %s:%s %s',
            \York\Helper\Date::getDate(),
            $this->getException()->getFile(),
            $this->getException()->getLine(),
            $this->getException()->getMessage()
        );

        return sprintf($sprintfArgs);
    }


    /**
     * @param string $level
     *
     * @return $this
     */
    protected function logDefaultMessage($level = \York\Logger\Manager::LEVEL_ERROR)
    {
        $this->log($this->getDefaultMessage(), $level);

        return $this;
    }

    /**
     * @return $this
     */
    protected function logDefault()
    {
        $this->log($this->getDefaultMessage());

        return $this;
    }

    /**
     * @param string    $message
     * @param string    $level
     *
     * @return $this
     */
    protected function log($message, $level = \York\Logger\Manager::LEVEL_ERROR)
    {
        \York\Dependency\Manager::getLogger()->log($message, $level);

        return $this;
    }

    /**
     * @param string $target
     */
    protected function reRunYork($target = '/error/app')
    {
        if (true === \York\Helper\Application::isCli()) {
            die('cannot rerun york cli app!');
        }

        $_SERVER['REQUEST_URI'] = $target;
        \Application\Configuration\Dependency::getAssetManager()->clear();

        $york = new \York\York();
        $york->run();

        die();
    }
}
