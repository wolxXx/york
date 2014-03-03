<?php
namespace York\Request;
/**
 * request manager
 *
 * @author wolxXx
 * @version 3.0
 * @package York\Request
 *
 */
class Manager{
	/**
	 * keys in the post data that is ignored in log method
	 *
	 * @var array
	 */
	protected $ignoredPostForLog = array('pass', 'password', 'passwort');

	/**
	 * flag if the request was made by an ajax call
	 *
	 * @var boolean
	 */
	protected $isAjax;

	/**
	 * flag if the request has post data
	 *
	 * @var boolean
	 */
	protected $isPost;

	/**
	 * flag if the request was made by a mobile device
	 * or the application environment is set to mobile
	 *
	 * @var boolean
	 */
	protected $isMobile;

	/**
	 * the url path
	 *
	 * @var string
	 */
	protected $path;

	/**
	 * an instance of a data object
	 *
	 * @var \York\Request\Data
	 */
	public $dataObject;

	/**
	 * an instance of the stack
	 *
	 * @var \York\Stack
	 */
	protected $stack;

	/**
	 * constructor
	 * initializes via init method
	 */
	public function __construct(){
		$this->init();
	}

	/**
	 * grabs the instance of the stack
	 * creates a new data object instance
	 * checks if request is ajax, post and mobile
	 */
	protected function init(){
		$this->stack = \York\Dependency\Manager::get('applicationConfiguration');
		$this->dataObject = new \York\Request\Data();
		$this->checkIfRequestIsAjax();
		$this->checkIfRequestIsPost();
		$this->checkIfRequestIsMobile();
	}

	/**
	 * checks if the request was made via ajax
	 *
	 * @return \York\Request\Manager
	 */
	protected function checkIfRequestIsAjax(){
		$this->isAjax = isset($_SERVER['HTTP_X_REQUESTED_WITH']);
		return $this;
	}

	/**
	 * checks if the request contains post data
	 *
	 * @return \York\Request\Manager
	 */
	protected function checkIfRequestIsPost(){
		$post = $this->dataObject->getRawPOST();
		$this->isPost = false === empty($post);
		return $this;
	}

	/**
	 * checks if the request was made from a mobile device
	 * or the application environment was set to mobile
	 *
	 * @return \York\Request\Manager
	 */
	protected function checkIfRequestIsMobile(){
		$this->isMobile = false;
		if('mobile' === $this->stack->getSafely('version', 'main')){
			$this->isMobile = true;
			return $this;
		}
		$detect = new \Mobile_Detect();
		if($detect->isMobile()){
			$this->isMobile = true;
		}
		return $this;
	}

	/**
	 * determines if the request is an ajax request
	 *
	 * @return boolean
	 */
	public function isAjax(){
		return $this->isAjax;
	}

	/**
	 * determines if the request is post request
	 *
	 * @return boolean
	 */
	public function isPost(){
		return $this->isPost;
	}

	/**
	 * determines if the request is mobile
	 *
	 * @return boolean
	 */
	public function isMobile(){
		return $this->isMobile;
	}

	/**
	 * logs the post data
	 * fields named "pass", "password" or "passwort" are replaced with ****
	 *
	 * @return Manager
	 */
	public function postLog(){
		if(false === $this->isPost()){
			return $this;
		}

		$delim = ' | ';
		$txt = \York\Helper\Date::getDate().$delim;
		$txt .= \York\Helper\Net::getUserIP().$delim;
		$txt .= true === \York\Auth\Manager::isLoggedIn()? 'true ('.\York\Auth\Manager::getUserNick().')'.$delim : 'false'.$delim;
		$txt .= \York\Helper\Net::getCurrentURI().$delim;
		$txt .= "\nvalues:\n";
		foreach($this->dataObject->getRawPOST() as $key => $value){
			if(in_array($key, $this->ignoredPostForLog)){
				$value = '****';
			}
			if(in_array($key, array('base64data'))){
				$value = '[base64data length: '.strlen($value).']';
			}
			if(true === is_array($value)){
				$newvalue = '';
				foreach($value as $x => $y){
					$newvalue .= "$x = $y, ";
				}
				$value = trim($newvalue);
			}
			$txt .= "$key: $value\n";
		}
		$txt .= "________________\n";
		\York\Dependency\Manager::get('logger')->log($txt, \York\Logger\Manager::LEVEL_LOG_POST);
		return $this;
	}
}
