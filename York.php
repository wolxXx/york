<?php
namespace York;

/**
 * York Control Central Station
 *
 * @package \York
 * @version $version$
 * @author  wolxXx
 */
class York
{

    /**
     * @var \Application\Configuration\Bootstrap
     */
    protected $bootstrap;

    /**
     * initialise
     */
    public function __construct()
    {
        $this->initAutoloader();
    }

    /**
     * @return $this
     */
    protected function initAutoloader()
    {
        require_once __DIR__ . '/Autoload/Manager.php';

        new \York\Autoload\Manager();
        require_once(\York\Helper\Application::getProjectRoot() . 'vendor/autoload.php');

        return $this;
    }

    /**
     * @return $this
     */
    public function checkRequirements()
    {
        \York\Requirement::Factory()->check();

        return $this;
    }

    /**
     * run the application
     */
    public function run()
    {
        try {
            $this->checkRequirements();

            \York\Dependency\Manager::getBootstrap()
                                    ->beforeRun()
                                    ->run()
                                    ->afterRun()
                                    ->beforeView()
                                    ->view()
                                    ->afterView()
            ;
        } catch (\Exception $exception) {
            $this->handleException($exception);
        }
    }

    /**
     * @param \Exception $exception
     *
     * @return \York\ErrorHandler\ErrorHandlerInterface
     */
    protected function getExceptionHandler(\Exception $exception)
    {
        switch (true) {
            case $exception instanceof \York\Exception\Redirect: {
                return new \York\ErrorHandler\Redirect();

                break;
            }
            case $exception instanceof \York\Exception\NoView: {
                return new \York\ErrorHandler\NoView();

                break;
            }
            case $exception instanceof \York\Exception\QueryGenerator: {
                return new \York\ErrorHandler\Database();

                break;
            }
            case $exception instanceof \York\Exception\Database: {
                return new \York\ErrorHandler\Database();
                break;
            }
            default: {
                return new \York\ErrorHandler\General();

                break;
            }
        }
    }

    public static function errorHandler()
    {
        \York\Helper\Application::dieDebug(func_get_args());
    }

    /**
     * @param \Exception $exception
     */
    protected function handleException(\Exception $exception)
    {
        $this
            ->getExceptionHandler($exception)
            ->setException($exception)
            ->handle()
        ;
    }
}
