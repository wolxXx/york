<?php
/**
 * Created by PhpStorm.
 * User: wolxxx
 * Date: 08.01.14
 * Time: 19:48
 */

namespace York\Auth;


interface ManagerInterface {
	/**
	 * checks if the provided passwod matches the saved one
	 *
	 * @param string $password
	 * @param \York\Database\FetchResult $user
	 * @return boolean
	 */
	public static function isUserPasswordOk($password, $user);

	/**
	 * hashes the password
	 *
	 * @param string $password
	 * @return string
	 */
	public static function hashPassword($password);

	/**
	 * salts the password
	 *
	 * @param string $password
	 * @return string
	 */
	public static function saltPassword($password);

	/**
	 * clears all auth session data
	 */
	public static function logout();


	/**
	 * creates auth session data
	 *
	 * @param \York\Database\FetchResult $result
	 */
	public static function login($result = null);

	/**
	 * setter for the user
	 *
	 * @param \York\Database\FetchResult $user
	 */
	public static function setUser($user);

	/**
	 * bans a user the time to ban was set in the defines file by BAN_TIME
	 */
	public static function ban();

	/**
	 * unbans a user
	 */
	public static function unBan();

	/**
	 * checks if a user is banned
	 *
	 * @return boolean
	 */
	public static function isBanned();

	/**
	 * returns the seconds the user will is banned
	 *
	 * @return integer
	 */
	public static function getRemainingBanTime();

	/**
	 * setter for the logged in state
	 *
	 * @param boolean $isLoggedIn
	 */
	public static function setIsLoggedIn($isLoggedIn = true);

	/**
	 * checks if user is logged in
	 *
	 * @return boolean
	 */
	public static function isLoggedIn();

	/**
	 * checks if user has access to the level
	 * eg requested page (admin) has level 3, if user has 2 it returns false
	 * if requested page (home) has access level 0, if user has at least 1, it returns true
	 *
	 * @param integer $level
	 * @return boolean
	 */
	public static function hasAccess($level);

	/**
	 * returns the amount of failed logins of the current user
	 *
	 * @return integer
	 */
	public static function getUserFailedLogins();

	/**
	 * returns the id of the current logged in user
	 *
	 * @return integer
	 */
	public static function getUserId();

	/**
	 * returns the type of the currently logged in user
	 *
	 * @return integer
	 */
	public static function getUserType();

	/**
	 * returns the nick name of the currently logged in user
	 *
	 * @return string
	 */
	public static function getUserNick();


	/**
	 * returns the email of the currently logged in user
	 *
	 * @return string
	 */
	public static function getUserEmail();

	/**
	 * returns the status of the currently logged in user
	 *
	 * @return integer
	 */
	public static function getUserStatus();

	/**
	 * returns the date of the last login of the currently logged in user
	 *
	 * @return \DateTime
	 */
	public static function getUserLastLogin();
}
