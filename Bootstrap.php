<?php
namespace York;
use York\Helper\Application;
use York\Logger\Database;
use York\Logger\File;
use York\Logger\Manager;
use York\Router;

/**
 * basic basement for mvc base
 * runs some before and after functions
 * initialises analysing of url
 * instanciates the controller
 * calls view from view manager
 *
 * @author wolxXx
 * @version 3.0
 * @package York
 */
abstract class Bootstrap{
	/**
	 * the cleared query string
	 *
	 * @var string
	 */
	public $request;

	/**
	 * contains the splitted url blocks of the $request
	 *
	 * @var array
	 */
	public $path;

	/**
	 * instance of view manager
	 *
	 * @var \York\View\Manager
	 */
	public $viewManager;

	/**
	 * instance of Model
	 *
	 * @var \York\Database\Model
	 */
	public $model;

	/**
	 * instance of Stack
	 *
	 * @var \York\Stack
	 */
	public $stack;

	/**
	 * instanciated object of a controller
	 *
	 * @var \York\Controller
	 */
	public $controller;

	/**
	 * instance of the router
	 *
	 * @var Router
	 */
	public $router;

	/**
	 * constructor
	 * gets all needed singleton instances
	 */
	public final function __construct(){
		$this->init();
	}

	/**
	 * get the config
	 */
	protected final function config(){
		$config = new \Application\Configuration\Host();
		$config->configureApplication();
		$config->configureHost();
		$config->checkConfig();
	}

	/**
	 * initing everything usefull
	 */
	private final function init(){
		if(false === isset($_SERVER['argv'])){
			set_exception_handler(function(){
				foreach(func_get_args() as $current){
					var_dump($current);
					echo ($current->getMessage());
				}
				die('WTF?!');
				\York\Helper::dieDebug(func_get_args());
			});
			set_error_handler(function(){
				call_user_func_array(array('\York\York', 'errorHandler'), func_get_args());
			}, -1);
		}
		$logPath = 'log/phperror.log';

		if(true === defined('LOGPATH')){
			$logPath = LOGPATH;
		}
		ini_set('error_log', $logPath);
		if(false === defined('STDIN') && '' === session_id()){
			session_start();
		}

		Manager::getInstance()->addLogger(new Database('log', Manager::LEVEL_ALL));
		Manager::getInstance()->addLogger(new File(__DIR__.'/log/york.log', Manager::LEVEL_ALL));
		Manager::getInstance()->log('test');

		$this->router = new Router();
		Application::grabModeAndVersion();
		Application::grabHostName();

		$this->stack = \York\Stack::getInstance();
		$this->config();

		$this->model = new \York\Database\Model();
		$this->viewManager = \York\View\Manager::getInstance();

		#\York\Helper::logToFile('URL: '.\York\Helper::getCurrentURL(), 'url');
	}

	/**
	 * called by mvc before the main run function
	 * can be overwriten by user's wanted own bootstrap class
	 * default behaviour: do the wolxXx style. do nothing :)
	 */
	public function beforeRun(){}

	/**
	 * called by mvc after the main run function
	 * can be overwriten by user's wanted own bootstrap class
	 * default behaviour: do the wolxXx style. do nothing :)
	 */
	public function afterRun(){}

	/**
	 * called by mvc before the main view function
	 * can be overwriten by user's wanted own bootstrap class
	 * default behaviour: do the wolxXx style. do nothing :)
	 */
	public function beforeView(){}

	/**
	 * called by mvc after  the main run function
	 * can be overwriten by user's wanted own bootstrap class
	 * default behaviour: do the wolxXx style. do nothing :)
	 */
	public function afterView(){}

	/**
	 * calls analyze
	 * instanciates controller
	 * calls controller's before run action
	 * calls controller's run action
	 * calls controller's after run action
	 */
	public final function run(){
		$this->analyzeRequest();
		$controller = $this->getController();
		$this->controller = new $controller();
		$this->stack->set('controller', $this->controller->__toString());
		call_user_func_array(array($this->controller, "setModels"), $this->path);
		call_user_func_array(array($this->controller, "setActionAndView"), $this->path);
		$this->checkRegisteredRedirect();
		call_user_func_array(array($this->controller, "setAccessRules"), $this->path);
		call_user_func_array(array($this->controller, "checkAccess"), $this->path);
		call_user_func_array(array($this->controller, "beforeRun"), $this->path);
		if(true === $this->controller->getRequest()->isPost()){
			$this->controller->postlog();
		}
		$this->checkRegisteredRedirect();
		call_user_func_array(array($this->controller, "run"), $this->path);
		$this->checkRegisteredRedirect();
		call_user_func_array(array($this->controller, "afterRun"), $this->path);
		$this->checkRegisteredRedirect();
	}

	/**
	 * checks if the controller registered a redirect
	 */
	protected function checkRegisteredRedirect(){
		if(null !== $this->controller->getRegisteredRedirect()){
			$this->controller->getRegisteredRedirect()->redirect();
		}
	}


	/**
	 * calls the view manager's view function
	 */
	public function view(){
		$this->viewManager->view($this->controller->getView());
	}

	/**
	 * splitts the request_uri from http request
	 * clears all get params
	 * trims all tailing slashes
	 */
	public function analyzeRequest(){
		$this->request = \York\Helper\Net::getCurrentURI();
		$this->path = explode('?', $this->request, 2);
		$this->request = trim($this->path[0], '/');
		$this->path = explode('/', $this->request);
		foreach($this->path as $key => $value){
			$this->path[$key] = urldecode($value);
		}
	}

	/**
	 * determines if a file exists that contains a controller class
	 * if no match is found, the cms controller is returned
	 *
	 * @return string
	 */
	public function getController(){
		$controllerName = ucfirst($this->path[0]);
		if(false === file_exists('Application/Controller/'.$controllerName.'.php')){
			$controllerName = 'Cms';
		}

		$controllerName = sprintf('\Application\Controller\%s', $controllerName);

		return $controllerName;
	}
}
