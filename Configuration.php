<?php
namespace York;
use York\Dependency\Manager as Dependency;
use York\Exception\Apocalypse;

/**
 * config base for having objects as config
 *
 * @author wolxXx
 * @version 3.0
 * @package York
 */
abstract class Configuration{
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
	public static $USER_TYPE_ADMIN = 2;

	/**
	 * an instance of the stack
	 * @var \York\Stack
	 */
	protected $stack;

	/**
	 * get an instance of the stack
	 */
	public final function __construct(){
		$this->stack = Dependency::get('applicationConfiguration');
		$this->stack->set('output.isVerboseEnabled', false);
		$this->stack->set('output.isDebugEnabled', false);
		$this->stack->set('output.isStandardEnabled', true);
	}

	/**
	 * configuration of the application
	 *
	 * @throws Apocalypse
	 */
	public function configureApplication(){
		throw new Apocalypse('please configure application');
	}

	/**
	 * configuration of the host
	 * place database credentials here
	 * configure whatever you want
	 *
	 * @throws Apocalypse
	 */
	public function configureHost(){
		throw new Apocalypse('please configure host');
	}

	/**
	 * checks if the minimal needed settings are done
	 *
	 * @throws Apocalypse
	 */
	public final function checkConfig(){
		$needed = array(
			Dependency::get('databaseConfiguration')->get('db_host'),
			Dependency::get('databaseConfiguration')->get('db_user'),
			Dependency::get('databaseConfiguration')->get('db_schema'),
			Dependency::get('databaseConfiguration')->get('db_pass')
		);
		if(true === in_array(null, $needed)){
			throw new Apocalypse('you need to specify db_host, db_user, db_schema, db_pass in configuration!');
		}
	}
}
