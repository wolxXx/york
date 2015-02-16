<?php
namespace York;

/**
 * the main controller which provides main functionality
 * this cannot be instantiated - this is abstract
 * other controllers should extend this class
 *
 * @see https://www.youtube.com/watch?v=vESqVS1f-Tg
 *
 * @package \York
 * @version $version$
 * @author wolxXx
 */
abstract class Controller
{
    /**
     * name of the action that is running
     *
     * @var string
     */
    protected $action;

    /**
     * the name of the view which should be displayed
     *
     * @var string
     */
    public $view;

    /**
     * an instance of the view manager
     *
     * @var \York\View\Manager
     */
    protected $viewManager;

    /**
     * an instance of the access checker
     *
     * @var \York\AccessCheck\Manager
     */
    protected $accessChecker;

    /**
     * information holder of the request
     *
     * @var \York\Request\Manager
     */
    protected $request;

    /**
     * version
     *
     * @var string
     */
    protected $version;

    /**
     * constructor
     *
     * generates the data, the modes, logs post data
     */
    public final function __construct()
    {
        $this->init();
    }

    /**
     * instance of the logger
     *
     * @var \York\Logger\Manager
     */
    protected $logger;

    /**
     * set if after the run method should be a redirect
     *
     * @var \York\Redirect
     */
    protected $registeredRedirect = null;

    /**
     * initialises the controller
     *
     * @return $this
     *
     * @throws Exception\Dependency
     * @throws Exception\NoView
     */
    private final function init()
    {
        $this->dependencyManager = \York\Dependency\Manager::getInstance();
        $this->viewManager = \York\Dependency\Manager::getViewManager();
        $this->request = new \York\Request\Manager();
        $this->initAccessChecker();
        $this->version = \York\Dependency\Manager::getApplicationConfiguration()->getSafely('version', 'main');

        try {
            $this->viewManager->setLayout(\York\Dependency\Manager::getApplicationConfiguration()->getSafely('version', 'main'));
        } catch (\York\Exception\General $exception) {
            $this->viewManager->setLayout();
        }

        $this->viewManager->set('isAjax', $this->request->isAjax());

        if ($this->request->isMobile() || 'mobile' === $this->version) {
            try {
                $this->viewManager->setLayout('mobile');
                $this->version = 'mobile';
            } catch (\York\Exception\General $exception) {
                $this->viewManager->setLayout();
            }
        }

        return $this;
    }

    /**
     * register a redirect
     *
     * @param string | \York\Redirect   $url
     * @param string                    $method
     *
     * @return $this
     */
    protected function registerRedirect($url, $method = 'redirect')
    {
        if (true === $url instanceof \York\Redirect) {
            $this->registeredRedirect = $url;

            return $this;
        }

        $this->registeredRedirect = new \York\Redirect($url, $method);

        return $this;
    }

    /**
     * return the registered redirect
     *
     * @return \York\Redirect | null
     */
    public function getRegisteredRedirect()
    {
        return $this->registeredRedirect;
    }

    /***
     * @return $this
     */
    public function removeRegisteredRedirect()
    {
        $this->registeredRedirect = null;

        return $this;
    }

    /**
     * sets the default access rule
     * everything is allowed!
     * overwrite this method for your controller if needed
     *
     * @return $this
     */
    public function setAccessRules()
    {
        $this->accessChecker->addRule(new \York\AccessCheck\Rule());

        return $this;
    }

    /**
     * creates a new instance of the access checker
     * and sets logged in and user type
     *
     * @return $this
     */
    private final function initAccessChecker()
    {
        $this->accessChecker = new \York\AccessCheck\Manager(\York\Auth\Manager::isLoggedIn());

        if (true === \York\Auth\Manager::isLoggedIn()) {
            $this->accessChecker->setUserLevel(\York\Auth\Manager::getUserType());
        }

        return $this;
    }

    /**
     * check if the requested action has any restrictions
     *
     * @return $this
     *
     * @throws \York\Exception\NotAllowed
     * @throws \York\Exception\AuthRequested
     */
    public final function checkAccess()
    {
        $authRequired = $this->accessChecker->requiresAuth($this->action);
        if (false === $authRequired) {
            return;
        }

        if (false === \York\Auth\Manager::isLoggedIn()) {
            throw new \York\Exception\AuthRequested();
        }

        if (false === $this->accessChecker->checkAccess($this->action)) {
            throw new \York\Exception\NotAllowed();
        }

        return $this;
    }

    /**
     * returns the currently active access checker
     *
     * @return \York\AccessCheck\Manager
     */
    public final function getAccessChecker()
    {
        return $this->accessChecker;
    }

    /**
     * getter for the current request object
     *
     * @return \York\Request\Manager
     */
    public function getRequest()
    {
        return $this->request;
    }

    /**
     * getter for the wished view to be displayed
     *
     * @return string
     */
    public function getView()
    {
        return $this->view;
    }

    /**
     * setter for the view
     *
     * @param string $view
     *
     * @return $this
     */
    protected function setView($view)
    {
        $this->view = $view;

        return $this;
    }

    /**
     * setter for the action
     *
     * @param string $action
     *
     * @return $this
     */
    protected function setAction($action)
    {
        $this->action = $action;

        return $this;
    }

    /**
     * getter for the action
     *
     * @return string
     */
    public function getAction()
    {
        return $this->action;
    }

    /**
     * default routing mechanism
     *
     * @return $this
     *
     * @throws \York\Exception\NoView
     */
    public function setActionAndView()
    {
        if (func_num_args() < 2 || '' === func_get_arg(1)) {
            if (false === method_exists(get_called_class(), 'indexAction')) {
                throw new \York\Exception\NoView('no index action in controller or requested method does not exist!');
            }

            $this->view = 'index';
            $this->action = 'index';

            return $this;
        }

        if (false === method_exists($this, func_get_arg(1) . 'Action') && false === $this->viewManager->viewExists(func_get_arg(1))) {
            throw new \York\Exception\NoView(func_get_arg(1) . ' is not a function and no view was found!');
        }

        $this->view = func_get_arg(1);

        if (true === method_exists($this, func_get_arg(1) . 'Action')) {
            $this->action = func_get_arg(1);

            return $this;
        }

        $this->action = 'noop';

        return $this;
    }

    /**
     * checks if the given args are found in the request and are not empty
     *
     * @return boolean
     */
    protected function isRequestOk()
    {
        foreach (func_get_args() as $current) {
            if ('' === $this->request->dataObject->getSavely($current, '')) {
                return false;
            }
        }

        return true;
    }

    /**
     * nothing
     * do nothing. seriously. take a seat and sit down. have a breath, a coffee. whatever you want!
     * there is a view file which does not need a controller function. impress e.g.
     *
     * @return $this
     */
    public final function noopAction()
    {
        return $this;
    }

    /**
     * returns the called class
     *
     * @return string
     */
    public final function __toString()
    {
        return strtolower(\York\Helper\String::getClassNameFromNamespace(get_called_class() . ''));
    }

    /**
     * is always be ran before any operation was made
     * useful for authorisation cases, measurements
     *
     * @return $this
     */
    public function beforeRun()
    {
        return $this;
    }

    /**
     * is always ran after the run function finished and before view rendering
     * useful for measurements
     *
     * @return $this
     */
    public function afterRun()
    {
        return $this;
    }

    /**
     * is called to initialize the needed loggers
     *
     * @return $this
     */
    public function initLogger()
    {
        return $this;
    }

    /**
     * @return $this
     *
     * @throws \York\Exception\Apocalypse
     */
    public final function run()
    {
        if (false === method_exists($this, $this->action . 'Action')) {
            throw new \York\Exception\Apocalypse($this->action . 'Action is not callable in ' . $this->__toString());
        }

        call_user_func_array(array($this, $this->action . 'Action'), func_get_args());

        return $this;
    }
}
