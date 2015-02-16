<?php
namespace York;

/**
 * redirect helper class
 *
 * @package \York
 * @version $version$
 * @author wolxXx
 */
class Redirect
{
    /**
     * the url that should be redirect to
     *
     * @var string
     */
    protected $url;

    /**
     * type of redirection
     *
     * @var string
     *
     * @see \York\Redirect::$redirect, \York\Redirect::$moved, \York\Redirect::$refresh, \York\Redirect::$historyBack
     */
    protected $method;

    /**
     * redirect type
     *
     * @var string
     */
    public static $redirect = 'redirect';

    /**
     * redirect type
     *
     * @var string
     */
    public static $moved = 'moved';

    /**
     * redirect type
     *
     * @var string
     */
    public static $refresh = 'refresh';

    /**
     * redirect type
     *
     * @var string
     */
    public static $historyBack = 'historyBack';

    /**
     * constructor
     *
     * @param string    $url
     * @param string    $method
     */
    public function __construct($url = null, $method = null)
    {
        $this
            ->setMethod($method)
            ->setUrl($url);
    }

    /**
     * @return $this
     */
    public static function Factory()
    {
        return new static();
    }

    /**
     * setter for the redirect url
     *
     * @param string $url
     *
     * @return $this
     */
    public function setUrl($url)
    {
        $this->url = $url;

        return $this;
    }

    /**
     * getter for the redirect url
     *
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * setter for the redirection type
     * default is direct redirect
     *
     * @param string | null $method
     *
     * @return $this
     */
    public function setMethod($method = null)
    {
        if (null === $method || false === isset(self::$$method)) {
            $method = self::$redirect;
        }

        $this->method = $method;

        return $this;
    }

    /**
     * getter for the redirect type
     *
     * @return string
     */
    public function getMethod()
    {
        return $this->method;
    }

    /**
     * the redirect caller
     *
     * calls the \York\Helper functions
     *
     * @covers \York\Helper::redirect
     */
    public function redirect()
    {
        if (null === $this->url && false === in_array($this->method, array(self::$historyBack, self::$refresh))) {
            throw new \York\Exception\Redirect('Redirect needs to have a url if not switching to history back or refresh');
        }

        call_user_func('\York\Helper\Application::' . $this->method, $this->url);
    }
}
