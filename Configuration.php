<?php
namespace York;
/**
 * config base for having objects as config
 *
 * @author wolxXx
 * @version 3.0
 * @package York
 */
abstract class Configuration{
	public static $USER_TYPE_ADMIN = 1;
	public static $AUTH_ACTIVATE_USER_BANNING = 'Auth.activateUserBanning';
	public static $AUTH_CREDENTIAL_USER_ACCESS = 'Auth.credentialsUserAccess';
	public static $AUTH_CREDENTIAL_USER_ID = 'Auth.credentialsUserId';
	public static $AUTH_LOGGED_IN = 'Auth.loggedIn';
	public static $AUTH_USER = 'Auth.user';
	public static $AUTH_FAILED_LOG_INS = 'Auth.failedLogins';
	public static $AUTH_USER_BANNED = 'Auth.banned';
	public static $AUTH_BAN_TIME = 'Auth.userBanTime';
	public static $BAN_TIME = 1337;
	public static $USER_STATUS_PENDING = 0;
	public static $USER_STATUS_ACTIVATED =  1;
	public static $USER_STATUS_BANNED = 2;
	public static $USER_TYPE_USUAL = 0;
	public static $USER_TYPE_EDITOR = 1;

	/**
	 * an instance of the stack
	 * @var \York\Stack
	 */
	protected $stack;

	/**
	 * get an instance of the stack
	 */
	public final function __construct(){
		$this->stack = \York\Dependency\Manager::get('applicationConfiguration');
	}

	/**
	 * configuration of the application
	 *
	 * @throws \York\Exception\Apocalypse
	 */
	public function configureApplication(){
		throw new \York\Exception\Apocalypse('please configure application');
	}

	/**
	 * configuration of the host
	 * place database credentials here
	 * configure whatever you want
	 *
	 * @throws \York\Exception\Apocalypse
	 */
	public function configureHost(){
		throw new \York\Exception\Apocalypse('please configure host');
	}

	/**
	 * checks if the minimal needed settings are done
	 *
	 * @throws \York\Exception\Apocalypse
	 */
	public final function checkConfig(){
		$needed = array(
			$this->stack->get('db_host'),
			$this->stack->get('db_user'),
			$this->stack->get('db_schema'),
			$this->stack->get('db_pass')
		);
		if(true === in_array(null, $needed)){
			throw new \York\Exception\Apocalypse('you need to specify db_host, db_user, db_schema, db_pass in configuration!');
		}
	}
}
