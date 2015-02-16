<?php
namespace Application\Controller;

/**
 * controller for the cms and static content stuff like faq, impress, contact, etc
 *
 * @package Application\Controller
 * @version 1.0
 * @author York Framework
 */
class Cms extends \York\Controller
{
    /**
     * @inheritdoc
     */
    function setAccessRules()
    {
        $this->accessChecker
            ->addRule(new \York\AccessCheck\Rule('*', false));
    }

    /**
     * @inheritdoc
     */
    public function setActionAndView()
    {
        $content = func_get_arg(0);

        if ('' === $content) {
            $this->action = 'index';
            $this->view = 'index';

            return $this;
        }

        if (true === method_exists($this, $content . 'Action')) {
            $this->action = $content;
            $this->view = $content;

            return $this;
        }

        if ($this->viewManager->viewExists($content)) {
            $this->action = 'noop';
            $this->view = $content;

            return $this;
        }

        if (false === \York\Autoload\Manager::isLoadable('\Application\Model\Manager\Content') || null === \Application\Configuration\Dependency::getContentManager()->getContent($content)) {
            throw new \York\Exception\NoView();
        }

        $this->action = 'cms';
        $this->view = 'cms';

        return $this;
    }

    /**
     * just displays an item from the database table cms
     *
     * @return $this
     *
     * @throws \York\Exception\NoView
     */
    public function cmsAction()
    {
        $cmsContent = \Application\Configuration\Dependency::getContentManager()->getContent(func_get_arg(0));
        if (null === $cmsContent || (false === $cmsContent->getIs_active() && false === \York\Auth\Manager::hasAccess(\Application\Configuration\Application::$USER_TYPE_ADMIN))) {
            throw new \York\Exception\NoView();
        }

        $this->viewManager->set('entry', $cmsContent);

        return $this;
    }

    /**
     * main starting index function
     *
     * @return $this
     */
    public function indexAction()
    {
        return $this;
    }

    /**
     * the error pages
     *
     * @return $this
     */
    public function errorAction()
    {
        if (null !== \Application\Configuration\Dependency::getSession()->getSafely('last_error')) {
            $this->viewManager->set('last_error', \Application\Configuration\Dependency::getSession()->get('last_error'));
            \Application\Configuration\Dependency::getSession()->set('last_error', null);
        } else {
            $this->viewManager->set('last_error', null);
        }

        $type = null;
        if (func_num_args() > 2) {
            $type = func_get_arg(1);
        }

        $this->viewManager->set('type', $type);

        if (true === in_array($this->viewManager->get('type'), array('404', 'no_view', 'noView'))) {
            header("HTTP/1.0 404 Not Found");
        } elseif (true === in_array($this->viewManager->get('type'), array('403', 'no_auth', 'noAuth', 'pending', 'banned'))) {
            header("HTTP/1.0 403 Access Denied");
        } elseif (true === in_array($this->viewManager->get('type'), array('500', 'app'))) {
            header("HTTP/1.0 500 Internal Server Error");
        }

        return $this;
    }
}
