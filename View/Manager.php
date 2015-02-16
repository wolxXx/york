<?php
namespace York\View;

/**
 * the loader / the bridge to the view
 * extracts the set vars to the view
 * handles partials
 * holds JavaScript, JavaScriptFiles, CSS and CSSFiles
 *
 * @package \York\View
 * @version $version$
 * @author wolxXx
 */
class Manager
{
    /**
     * params and variables to the views
     *
     * @var array
     */
    private $params = array();

    /**
     * the name of the layout
     *
     * @var string
     */
    private $layout = 'main';

    /**
     * the default layout
     *
     * @var string
     */
    private $defaultLayout = 'Main';

    /**
     * the path to the layouts
     *
     * @var string
     */
    protected $layoutPath = '';

    /**
     * the path to the views
     *
     * @var string
     */
    protected $viewPath = '';

    /**
     * @var ItemAbstract | null
     */
    protected $viewInstance = null;

    /**
     * @var \York\Helper\Translator
     */
    public $translator;

    /**
     * constructor is private because of singleton access
     */
    public final function __construct()
    {
        $this->params = array();
        $this
            ->setLayoutPath(\York\Dependency\Manager::getApplicationConfiguration()->getSafely('layoutPath', \York\Helper\Application::getApplicationRoot() . 'View' . DIRECTORY_SEPARATOR . 'Layout'))
            ->setLayout($this->layout)
            ->setViewPath(\York\Dependency\Manager::getApplicationConfiguration()->getSafely('viewPath', \York\Helper\Application::getApplicationRoot() . 'View' . DIRECTORY_SEPARATOR))
        ;

        $this->translator = \York\Dependency\Manager::getTranslator();
    }

    /**
     * @param \York\View\ItemInterface $instance
     *
     * @return $this
     */
    public final function setViewInstance($instance)
    {
        $this->viewInstance = $instance;

        return $this;
    }

    /**
     * @return \York\View\ItemInterface
     */
    public final function getViewInstance()
    {
        return $this->viewInstance;
    }

    /**
     * setter for the view path
     *
     * @param string $path
     *
     * @return $this
     */
    public function setViewPath($path)
    {
        $this->viewPath = \York\Helper\String::addTailingSlashIfNeeded($path);

        return $this;
    }

    /**
     * getter for the view path
     *
     * @return string
     */
    public function getViewPath()
    {
        return $this->viewPath;
    }

    /**
     * setter for the layout path
     *
     * @param string $path
     *
     * @return $this
     */
    public function setLayoutPath($path)
    {
        $this->layoutPath = \York\Helper\String::addTailingSlashIfNeeded($path);

        return $this;
    }

    /**
     * getter for the layout path
     *
     * @return string
     */
    public function getLayoutPath()
    {
        return $this->layoutPath;
    }

    /**
     * sets a variable
     *
     * @param string    $key
     * @param mixed     $value
     *
     * @return $this
     */
    public function set($key, $value)
    {
        $this->params[$key] = $value;

        return $this;
    }

    /**
     * gets a variable or null if not defined
     *
     * @param string $key
     *
     * @return mixed | null
     */
    function get($key)
    {
        if (true === array_key_exists($key, $this->params)) {
            return $this->params[$key];
        }

        return null;
    }

    /**
     * sets a layout
     *
     * @param string $name
     *
     * @return $this
     *
     * @throws \York\Exception\NoView
     */
    function setLayout($name = 'main')
    {
        $this->layout = $name;
        $path = $this->layoutPath . $this->layout . '.php';

        if (false === file_exists($path)) {
            throw new \York\Exception\NoView(sprintf('layout "%s" not found!', $name));
        }

        return $this;
    }

    /**
     * gets the name of the set layout
     *
     * @return string
     */
    function getLayout()
    {
        return $this->layout;
    }

    /**
     * determinate if a view file with $name exists
     *
     * @param string $name
     *
     * @return boolean
     */
    function viewExists($name)
    {
        return null !== $this->getView($name);
    }

    /**
     * retrieves the path of a file
     * if a prefix is set (layout) it checks if there exists a special view
     * if not the default view is returned if one exists
     * if not null is returned
     *
     * @param string $fileName
     *
     * @return string|null
     */
    function getView($fileName)
    {
        $prefix = \York\Helper\String::addTailingSlashIfNeeded('Main');

        if ($this->layout !== $this->defaultLayout) {
            $prefix = \York\Helper\String::addTailingSlashIfNeeded($this->layout);
        }

        $possibleMatches = array(
            #views/search/index
            $this->viewPath . $prefix . strtolower(\York\Dependency\Manager::getApplicationConfiguration()->get('controller')) . DIRECTORY_SEPARATOR . $fileName,

            #views/search/index
            $this->viewPath . 'Main/' . strtolower(\York\Dependency\Manager::getApplicationConfiguration()->get('controller')) . DIRECTORY_SEPARATOR . $fileName,

            #'views/mobile/api/foo'
            $this->viewPath . $prefix . \York\Dependency\Manager::getApplicationConfiguration()->get('controller') . DIRECTORY_SEPARATOR . $fileName,

            #'/views/mobile/foo'
            $this->viewPath . $prefix . $fileName,

            #'/views/api/foo'
            $this->viewPath . \York\Dependency\Manager::getApplicationConfiguration()->get('controller') . DIRECTORY_SEPARATOR . $fileName,

            #'/views/foo'
            $this->viewPath . $fileName,

            #'/views/foo'
            $this->viewPath . 'Main/' . $fileName,

            #'/views/foo'
            $this->viewPath . 'Main/' . \York\Dependency\Manager::getApplicationConfiguration()->get('controller') . '/' . $fileName
        );

        foreach ($possibleMatches as $current) {
            $current .= '.php';

            if (true === is_file($current)) {
                return $current;
            }
        }

        return null;
    }

    /**
     * runs the view, buffers it, and then calls the layout
     *
     * @param string $fileName
     *
     * @return \York\View\Manager
     *
     * @throws \York\Exception\NoView
     */
    function view($fileName = null)
    {
        $this->params['content'] = '';

        if (null !== $this->getViewInstance()) {
            $this->params['content'] = $this->getViewInstance()->prepare()->render()->getContent();
        } else {
            $file = $this->getView($fileName);

            if (null === $file || null === $fileName) {
                throw new \York\Exception\NoView($fileName . ' not found....');
            }

            extract($this->params);
            ob_start();

            require $file;

            $this->params['content'] = ob_get_clean();
        }

        extract($this->params);

        if (true === $this->get('isAjax')) {
            echo $this->params['content'];

            return $this;
        }

        /**
         * load the layout view file
         *
         * @todo implement as class like views...
         */
        if (false === file_exists($this->layoutPath . $this->layout . '.php')) {
            $this->layout = $this->defaultLayout;
        }

        $path = sprintf('%s%s.php', $this->layoutPath, $this->layout);

        require($path);

        return $this;
    }

    /**
     * tries to render a partial view
     * if $passtrough is set, data contains the datas
     *
     * @param string    $name
     * @param mixed     $datas
     * @param boolean   $passtrough
     *
     * @return $this
     */
    function partial($name, $datas = null, $passtrough = false)
    {
        $file = $this->getView($name);
        if (null === $file) {
            \York\Dependency\Manager::getLogger()->log(sprintf('partial "%s" not found!', $name), \York\Logger\Level::DEBUG);

            return $this;
        }

        if (true === is_array($datas) && false === $passtrough) {
            foreach ($datas as $key => $value) {
                $$key = $value;
            }
        } else {
            $this->params['data'] = $datas;
        }

        extract($this->params);

        require $file;

        return $this;
    }
}
