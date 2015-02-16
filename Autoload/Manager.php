<?php
namespace York\Autoload;

/**
 * object orientated auto loading mechanism
 *
 * @package York\Autoload
 * @version $version$
 * @author  wolxXx
 */
final class Manager
{
    /**
     * @var null | string
     */
    protected static $pathToApplication = null;

    /***
     * @var null | string
     */
    protected static $pathToLibrary = null;

    /**
     * constructor
     * checks if there exists a map, creates it if not, and takes it
     * grabs the paths, registers spl auto loader
     *
     * if the $path param is null it takes get current working directory!
     */
    public function __construct()
    {
        spl_autoload_register(array($this, 'loadClass'), true, true);
    }


    /**
     * retrieves the file path for the given class name
     *
     * @param string $className
     *
     * @return string
     */
    protected static function getResolvedPathForClassName($className)
    {
        return str_replace('\\', '/', $className) . '.php';
    }

    /**
     * retrieves the file path for the given class name and prepends "Library"
     *
     * @param string $className
     *
     * @return string
     */
    protected static function getResolvedPathForClassNameInLibrary($className)
    {

        if ('wolxxx' === basename(realpath(__DIR__ . '/../../'))) {

            return __DIR__ . '/../../' . self::getResolvedPathForClassName(lcfirst($className));
        }

        return __DIR__ . '/../../' . self::getResolvedPathForClassName($className);

    }

    /**
     * retrieves the file path for the given class name and prepends "Library"
     *
     * @param string $className
     *
     * @return string
     */
    protected static function getResolvedPathForClassNameInApplication($className)
    {
        if (null === self::$pathToApplication) {
            if ('wolxxx' === basename(realpath(__DIR__ . '/../../'))) {
                self::$pathToApplication = realpath(__DIR__ . '/../../../../');
            } else {
                self::$pathToApplication = realpath(__DIR__ . '/../../../../../');
            }

            self::$pathToApplication .= '/';
        }

        return self::$pathToApplication . self::getResolvedPathForClassName($className);
    }

    /**
     * checker if the given className is loadable via this autoloader
     *
     * @param string $className
     *
     * @return boolean
     */
    public static function isLoadable($className)
    {
        try {
            new \York\FileSystem\File(self::getResolvedPathForClassNameInLibrary($className));

            return true;
        } catch (\York\Exception\FileSystem $exception) {
        }

        try {
            new \York\FileSystem\File(self::getResolvedPathForClassNameInApplication($className));
            return true;
        } catch (\York\Exception\FileSystem $exception) {

        }

        return false;
    }

    /**
     * loads a requested file
     * checks for default classes
     *
     * @param string $className
     */
    public function loadClass($className)
    {
        $nameSpace = explode('\\', $className)[0];

        if (false === in_array($nameSpace, array('York', 'Application'))) {
            return;
        }

        if ('York' === $nameSpace) {
            $resolvedClassPathInLibrary = self::getResolvedPathForClassNameInLibrary($className);

            if (false === file_exists($resolvedClassPathInLibrary)) {
                die('cannot load york file: '.$className);
            }

            require_once $resolvedClassPathInLibrary;

            return;
        }

        $resolvedClassPath = self::getResolvedPathForClassNameInApplication($className);

        if (false === file_exists($resolvedClassPath)) {
            debug_print_backtrace();
            die('cannot load application file: '. $className);
        }

        require_once $resolvedClassPath;
    }
}
