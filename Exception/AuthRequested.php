<?php
namespace York\Exception;

/**
 * exception for needed auth
 *
 * @package York\Exception
 * @version $version$
 * @author wolxXx
 */
class AuthRequested extends \York\Exception\Auth
{
    /**
     * @inheritdoc
     */
    public function __construct()
    {
        \York\Dependency\Manager::getSession()->set('redirect', \York\Helper\Net::getCurrentURI());
        \York\Helper\Application::redirect('/auth/login');
    }
}
