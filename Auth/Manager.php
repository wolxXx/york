<?php
namespace York\Auth;
use Application\Helper;
use York\Configuration;
use York\Database\Accessor\Factory;
use York\Database\Model;
use York\Exception\Auth;
use York\Helper\Application;
use York\Helper\Date;
use York\Helper\Password;
use York\Helper\Translator;
use York\Request\Data;
use York\View\Splash\Manager as Splash;

/**
 * auth class
 * should be used as static class, no chance to instantiate
 * has methods: login, logout, isLoggedIn, getUserData
 *
 * the data is saved in the stack, so it is available after page refresh
 *
 * @author wolxXx
 * @version 3.0
 * @package York
 * @subpackage Auth
 */
abstract class Manager{
	/**
	 * the user
	 *
	 * @var \York\Database\FetchResult
	 */
	public static $User;

	/**
	 * checks if the provided passwod matches the saved one
	 *
	 * @param string $password
	 * @param \York\Database\FetchResult $user
	 * @return boolean
	 */
	public static function isUserPasswordOk($password, $user){
		return $user->password === self::hashPassword($password);
	}

	/**
	 * hashes the password
	 *
	 * @param string $password
	 * @return string
	 */
	public static function hashPassword($password){
		return md5(self::saltPassword($password));
	}

	/**
	 * salts the password
	 *
	 * @param string $password
	 * @return string
	 */
	public static function saltPassword($password){
		return $password;
	}

	/**
	 * getter for the stack
	 *
	 * @param string $key
	 * @return mixed
	 */
	private static function get($key){
		return \York\Stack::getInstance()->get($key);
	}
	/**
	 * setter for the stack
	 *
	 * @param string $key
	 * @param mixed $value
	 */
	private static function set($key, $value){
		\York\Stack::getInstance()->set($key, $value);
	}

	/**
	 * clears all auth session data
	 */
	public static function logout(){
		self::set(Configuration::$AUTH_LOGGED_IN, false);
		self::set(Configuration::$AUTH_USER, null);
		self::set(Configuration::$AUTH_FAILED_LOG_INS, 0);
	}

	/**
	 * increases the counter of failed logins
	 */
	protected static function increaseFailures(){
		self::set(Configuration::$AUTH_FAILED_LOG_INS, self::get(Configuration::$AUTH_FAILED_LOG_INS) + 1);
	}

	/**
	 * checks if the user shall be banned after failed login
	 * generates automaticaly a new password for the given user
	 */
	protected static function checkBanAfterFailedLogin($result){
		if(true === self::get(Configuration::$AUTH_ACTIVATE_USER_BANNING)){
			self::increaseFailures();
			if(self::get(Configuration::$AUTH_FAILED_LOG_INS) > 2){
				self::set(Configuration::$AUTH_USER_BANNED, time() + (int) Configuration::$AUTH_BAN_TIME);
				self::set(Configuration::$AUTH_FAILED_LOG_INS, 0);
				if(method_exists('Helper', 'sendBanMail')){
					Helper::sendBanMail();
					Helper::sendBanMail($result);
				}
				$password = Password::generatePassword();
				Factory::getUpdateObject('user', $result->id)
					->set('password', md5($password))
					->update();
			}
		}
	}

	/**
	 * creates auth session data
	 *
	 * @param \York\Database\FetchResult $result
	 * @todo remove redirects!!!
	 */
	public static function login($result = null){
		/**
		 * proposal for new login mechanism
		 * try to log in, throw exceptions if needed!
		 */

		$model = new Model();
		$dataObject = Data::getInstance();

		/**
		 * get the user from the database if result does not provide a database result
		 * get the id
		 */
		if(null === $result){
			$key = self::get(Configuration::$AUTH_CREDENTIAL_USER_ID);
			$value = $dataObject->get(self::get(Configuration::$AUTH_CREDENTIAL_USER_ID));
			$result = $model->findOne('user', $value, $key);
		}
		/**
		 * is a user found with this credentials?
		 */
		if(null === $result){
			self::checkBanAfterFailedLogin($result);
			Splash::addText(Translator::translate('Unbekannter Nutzer'));
			Application::redirect('/auth/login');
		}
		/**
		 * well a user was found, is his access matching to the database one's
		 */

		if($result->password !== md5($dataObject->get(self::get(Configuration::$AUTH_CREDENTIAL_USER_ACCESS)))){
			self::checkBanAfterFailedLogin($result);
			Splash::addText(Translator::translate('Falsches Passwort!'));
			Application::redirect('/auth/login');
		}
		if(Configuration::$USER_STATUS_BANNED == $result->status){
			Application::redirect('/error/banned');
		}
		if(Configuration::$USER_STATUS_PENDING == $result->status){
			Application::redirect('/auth/pending');
		}
		unset($result->password);
		self::set(Configuration::$AUTH_LOGGED_IN, true);
		self::setUser($result);
		Factory::getUpdateObject('user', $result->id)
			->set('lastlog', Date::getDate())
			->update();
	}

	/**
	 * setter for the user
	 *
	 * @param \York\Database\FetchResult $user
	 */
	public static function setUser($user){
		self::set(Configuration::$AUTH_USER, $user);
	}

	/**
	 * bans a user the time to ban was set in the defines file by BAN_TIME
	 */
	public static function ban(){
		self::set(Configuration::$AUTH_USER_BANNED, time() + Configuration::$BAN_TIME);
		Application::refresh();
	}

	/**
	 * unbans a user
	 */
	public static function unBan(){
		self::set(Configuration::$AUTH_USER_BANNED, time() -1);
	}

	/**
	 * checks if a user is banned
	 *
	 * @return boolean
	 */
	public static function isBanned(){
		return self::getRemainingBanTime() > 0;
	}

	/**
	 * returns the seconds the user will is banned
	 *
	 * @return integer
	 */
	public static function getRemainingBanTime(){
		return self::get(Configuration::$AUTH_USER_BANNED) - time();
	}

	/**
	 * setter for the logged in state
	 *
	 * @param boolean $isLoggedIn
	 */
	public static function setIsLoggedIn($isLoggedIn = true){
		self::set(Configuration::$AUTH_LOGGED_IN, $isLoggedIn);
	}

	/**
	 * checks if user is logged in
	 *
	 * @return boolean
	 */
	public static function isLoggedIn(){
		return true == self::get(Configuration::$AUTH_LOGGED_IN);
	}

	/**
	 * checks if user has access to the level
	 * eg requested page (admin) has level 3, if user has 2 it returns false
	 * if requested page (home) has access level 0, if user has at least 1, it returns true
	 *
	 * @param integer $level
	 * @return boolean
	 */
	public static function hasAccess($level){
		if(false === self::isLoggedIn()){
			return false;
		}
		return $level <= self::getUserType();
	}

	/**
	 * returns the amount of failed logins of the current user
	 *
	 * @return integer
	 */
	public static function getUserFailedLogins(){
		return self::get(Configuration::$AUTH_FAILED_LOG_INS);
	}

	/**
	 * shortcut for user getting
	 * @return mixed
	 * @throws Auth
	 */
	private static function getUser(){
		if(false === self::isLoggedIn()){
			throw new Auth('User is not logged in! cannot return user\'s properties!!');
		}
		return self::get(Configuration::$AUTH_USER);
	}

	/**
	 * returns the id of the current logged in user
	 *
	 * @return integer
	 */
	public static function getUserId(){
		return self::getUser()->id;
	}

	/**
	 * returns the type of the currently logged in user
	 *
	 * @return integer
	 */
	public static function getUserType(){

		return self::getUser()->type;
	}

	/**
	 * returns the nick name of the currently logged in user
	 *
	 * @return string
	 */
	public static function getUserNick(){
		return self::getUser()->nick;
	}


	/**
	 * returns the email of the currently logged in user
	 *
	 * @return string
	 */
	public static function getUserEmail(){
		return self::getUser()->email;
	}

	/**
	 * returns the status of the currently logged in user
	 *
	 * @return integer
	 */
	public static function getUserStatus(){
		return self::getUser()->status;
	}

	/**
	 * returns the date of the last login of the currently logged in user
	 *
	 * @return \DateTime
	 */
	public static function getUserLastLogin(){
		return self::getUser()->lastlog;
	}
}
