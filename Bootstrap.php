<?php
namespace York;

/**
 * basic basement for mvc base
 * runs some before and after functions
 * initialises analysing of url
 * instantiates the controller
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
	 * contains the split url blocks of the $request
	 *
	 * @var string[]
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
	 * instantiated object of a controller
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
	 * init everything useful
	 */
	private final function init(){
		if(false === isset($_SERVER['argv'])){
			set_exception_handler(function($exception){
				$this->handleException($exception);
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

		$this->router = new Router();

		\York\Helper\Application::grabModeAndVersion();
		\York\Helper\Application::grabHostName();

		$this->stack = \York\Dependency\Manager::get('applicationConfiguration');
		$this->config();

		$this->model = new \York\Database\Model();
		$this->viewManager = \York\Dependency\Manager::get('viewManager');
	}

	/**
	 * dedicated exception display handler
	 *
	 *
	 * @param \Exception $exception
	 */
	public function handleException(\Exception $exception){
		?>
		<h1>Error: <?= $exception->getMessage() ?></h1>
		Occurred in file: <?= $exception->getFile() ?> at line <?= $exception->getLine() ?><br />
		Trace:
		<? foreach($exception->getTrace() as $trace): ?>
			<ul>
				<? if(isset($trace['file'])): ?>
					<li>
						File: <?= $trace['file'] ?>
					</li>
				<? endif ?>
				<? if(isset($trace['line'])): ?>
					<li>
						Line: <?= $trace['line'] ?>
					</li>
				<? endif ?>
				<li>
					Function: <?= $trace['function'] ?>
				</li>
				<? if(isset($trace['type'])): ?>
					<li>
						Accessor: <?= $trace['type'] ?>
					</li>
				<? endif ?>
				<? if(isset($trace['type'])): ?>
					<li>
						Args: <? var_dump($trace['args']) ?>
					</li>
				<? endif ?>
			</ul>
		<? endforeach ?>

		<?
		die();
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
	public function run(){
		$this->analyzeRequest();
		$controller = $this->getController();
		\York\Dependency\Manager::get('applicationConfiguration')->set('controller', \York\Helper\String::getClassNameFromNamespace($controller));
		$this->controller = new $controller();
		call_user_func_array(array($this->controller, "initLogger"), $this->path);
		call_user_func_array(array($this->controller, "setActionAndView"), $this->path);
		$this->checkRegisteredRedirect();
		call_user_func_array(array($this->controller, "setAccessRules"), $this->path);
		call_user_func_array(array($this->controller, "checkAccess"), $this->path);
		$this->checkRegisteredRedirect();
		call_user_func_array(array($this->controller, "beforeRun"), $this->path);
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
		if(false === \York\Autoload\Manager::isLoadable('\Application\Controller\\'.$controllerName)){
			$controllerName = 'Cms';
		}

		$controllerName = sprintf('\Application\Controller\%s', $controllerName);

		return $controllerName;
	}

	public function databaseError(\York\Exception\Database $exception){
		die('a database error occured. sorry for that!');
	}
}
