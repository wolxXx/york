<?php
namespace York\ErrorHandler;

/**
 * interface for ErrorHandlers
 *
 * @package York\ErrorHandler
 * @version $version$
 * @author wolxXx
 */
interface ErrorHandlerInterface
{
    /**
     * instantiate
     */
    public function __construct();

    /**
     * @param \Exception $exception
     *
     * @return $this
     */
    public static function Factory(\Exception $exception);

    /**
     * @param \Exception $exception
     *
     * @return $this
     */
    public function setException(\Exception $exception);

    /**
     * @return \Exception
     */
    public function getException();

    /**
     * @return $this
     */
    public function handle();
}
