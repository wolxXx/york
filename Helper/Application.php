<?php
namespace York\Helper;

/**
 * application helper utilities class
 *
 * @package York\Helper
 * @version $version$
 * @author wolxXx
 */
class Application
{
    /**
     * checks if the application runs in cli mode
     *
     * @return boolean
     */
    public static function isCli()
    {
        return php_sapi_name() === 'cli';
    }

    /**
     * debugs all given params and dies
     *
     * @codeCoverageIgnore
     */
    public static function dieDebug()
    {
        foreach (func_get_args() as $current) {
            self::debug($current);
        }

        die('die debug called. stopping here...' . PHP_EOL);
    }

    /**
     * debugs all given params
     *
     * @codeCoverageIgnore
     */
    public static function debug()
    {
        $backtrace = debug_backtrace(true);
        $trace = $backtrace[0];

        if (__FILE__ === $trace['file']) {
            $trace = $backtrace[1];
        }

        $line = isset($trace['line']) ? $trace['line'] : 666;
        $file = isset($trace['file']) ? $trace['file'] : 'somewhere';

        $pre = '';
        $post = '';
        $last = '____________________' . PHP_EOL . PHP_EOL;

        if (false === self::isCli()) {
            $pre = '<div class="debug"><pre>';
            $post = '</pre>';
            $last = '</div>';
        }

        $text = 'debug from ' . (str_replace(getcwd(), '', $file)) . ' line ' . $line . ':' . PHP_EOL;

        echo sprintf('%s%s%s', $pre, $text, $post);

        foreach (func_get_args() as $arg) {
            var_dump($arg);
        }

        echo $last;

    }

    /**
     * @param string $path
     *
     * @return string
     */
    public static function getRelativePathInApplication($path)
    {
        return trim(str_replace(self::getDocRoot(), '', $path), ' /');
    }

    /**
     * provides the path of the docroot with tailing slash
     *
     * @return string
     */
    public static function getDocRoot()
    {
        return self::getProjectRoot() . 'docroot' . DIRECTORY_SEPARATOR;
    }

    /**
     * retrieves the project root path
     *
     * @return string
     */
    public static function getProjectRoot()
    {
        $path = realpath(__DIR__ . '/../../../../../');

        if ('wolxxx' === basename(realpath(__DIR__ . '/../../'))) {
            $path = realpath(__DIR__ . '/../../../../');
        }

        return $path . DIRECTORY_SEPARATOR;
    }

    /**
     * retrieves the application root
     *
     * @return string
     */
    public static function getApplicationRoot()
    {
        return self::getProjectRoot() . 'Application' . DIRECTORY_SEPARATOR;
    }

    /**
     * redirects to $url or reloads the site if $url is null
     *
     * @param string $url
     *
     * @codeCoverageIgnore
     */
    public static function redirect($url = null)
    {
        if (null === $url) {
            $url = '/';

            if (true === isset($_SERVER['REQUEST_URI'])) {
                $url = $_SERVER['REQUEST_URI'];
            }
        }

        header('Location:' . $url);

        die();
    }

    /**
     * redirects with telling the browser that the target moved
     *
     * @param string $url
     *
     * @codeCoverageIgnore
     */
    public static function moved($url)
    {
        header("HTTP/1.1 301 Moved Permanently");
        self::redirect($url);
    }

    /**
     * sends the user back to where he came ;)
     * the $redirect param is a fallback
     *
     * @param string $redirect
     *
     * @codeCoverageIgnore
     */
    public static function historyBack($redirect = '/')
    {
        if (true === isset($_SERVER['HTTP_REFERER'])) {
            $redirect = $_SERVER['HTTP_REFERER'];
        }

        self::redirect($redirect);
    }

    /**
     * reloads the current site
     *
     * @codeCoverageIgnore
     */
    public static function refresh()
    {
        self::redirect();
    }

    /**
     * grab the host name
     * if it was set before
     * saves it in the default stack
     */
    public static function grabHostName()
    {
        \York\Dependency\Manager::getApplicationConfiguration()->set('hostname', php_uname("n"));
    }

    /**
     * grabs the version and mode of the application
     * it is grabbed from the apache directive
     * SetEnv APPLICATION_ENV "main-dev"
     * version    €{main, mobile, ..}
     * mode        €{production, dev, ..}
     * saves it in the default stack
     */
    public static function grabModeAndVersion()
    {
        if (false === getenv('APPLICATION_ENV')) {
            putenv('APPLICATION_ENV=main-dev');
        }

        $split = explode('-', getenv('APPLICATION_ENV'));
        $version = $split[0];
        $mode = $split[1];
        \York\Dependency\Manager::getApplicationConfiguration()
            ->set('version', $version)
            ->set('mode', $mode)
        ;
    }

    /**
     * transcodes the php error codes to string
     *
     * @param integer $code
     *
     * @return string
     */
    public static function errorCodeToString($code)
    {
        $return = '';
        switch ($code) {
            case E_ERROR:
                $return = 'E_ERROR'; // 1

                break;

            case E_WARNING:
                $return = 'E_WARNING'; // 2

                break;

            case E_PARSE:
                $return = 'E_PARSE'; // 4

                break;

            case E_NOTICE:
                $return = 'E_NOTICE'; // 8

                break;

            case E_CORE_ERROR:
                $return = 'E_CORE_ERROR'; // 16

                break;

            case E_CORE_WARNING:
                $return = 'E_CORE_WARNING'; // 32

                break;

            case E_COMPILE_ERROR:
                $return = 'E_COMPILE_ERROR'; // 64

                break;

            case E_COMPILE_WARNING:
                $return = 'E_COMPILE_WARNING'; // 128

                break;

            case E_USER_ERROR:
                $return = 'E_USER_ERROR'; // 256

                break;

            case E_USER_WARNING:
                $return = 'E_USER_WARNING'; // 512

                break;

            case E_USER_NOTICE:
                $return = 'E_USER_NOTICE'; // 1024

                break;

            case E_STRICT:
                $return = 'E_STRICT'; // 2048

                break;

            case E_RECOVERABLE_ERROR:
                $return = 'E_RECOVERABLE_ERROR'; // 4096

                break;

            case E_DEPRECATED:
                $return = 'E_DEPRECATED'; // 8192

                break;

            case E_USER_DEPRECATED:
                $return = 'E_USER_DEPRECATED'; // 16384

                break;
        }

        return $return;
    }
}
