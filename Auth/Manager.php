<?php
namespace York\Auth;

/**
 * auth class
 * should be used as static class, no chance to instantiate
 *
 * the data is saved in the stack, so it is available after page refresh
 *
 * @package York\Auth
 * @version $version$
 * @author  wolxXx
 */
abstract class Manager implements ManagerInterface
{
    /**
     * the user
     *
     * @var \Application\Model\User
     */
    public static $User;

    /**
     * @inheritdoc
     */
    public static function isUserPasswordOk($password, $user)
    {
        return $user->getPassword() === self::hashPassword($password);
    }

    /**
     * @inheritdoc
     */
    public static function hashPassword($password)
    {
        return md5(self::saltPassword($password));
    }

    /**
     * @inheritdoc
     */
    public static function saltPassword($password)
    {
        return $password;
    }

    /**
     * getter for the application wide stack
     *
     * @param string $key
     *
     * @return mixed
     */
    private static function get($key)
    {
        return \York\Dependency\Manager::getApplicationConfiguration()->getSafely($key);
    }

    /**
     * setter for the application wide stack
     *
     * @param string    $key
     * @param mixed     $value
     */
    private static function set($key, $value)
    {
        \York\Dependency\Manager::getApplicationConfiguration()->set($key, $value);
    }

    /**
     * getter for the session stack
     *
     * @param string $key
     *
     * @return mixed
     */
    private static function getPersisted($key)
    {
        return \York\Dependency\Manager::getSession()->getSafely($key);
    }

    /**
     * setter for the session stack
     *
     * @param string    $key
     * @param mixed     $value
     */
    private static function setPersist($key, $value)
    {
        \York\Dependency\Manager::getSession()->set($key, $value);
    }

    /**
     * clears all auth session data
     */
    public static function logout()
    {
        self::setPersist(\York\Configuration::$AUTH_LOGGED_IN, false);
        self::setPersist(\York\Configuration::$AUTH_USER, null);
        self::setPersist(\York\Configuration::$AUTH_FAILED_LOG_INS, 0);
    }

    /**
     * increases the counter of failed logins
     */
    protected static function increaseFailures()
    {
        self::setPersist(\York\Configuration::$AUTH_FAILED_LOG_INS, self::getPersisted(\York\Configuration::$AUTH_FAILED_LOG_INS) + 1);
    }

    /**
     * checks if the user shall be banned after failed login
     * generates automaticaly a new password for the given user
     */
    protected static function checkBanAfterFailedLogin($result)
    {
        if (true === self::get(\York\Configuration::$AUTH_ACTIVATE_USER_BANNING)) {
            self::increaseFailures();

            if (self::getPersisted(\York\Configuration::$AUTH_FAILED_LOG_INS) > 2) {
                self::setPersist(\York\Configuration::$AUTH_USER_BANNED, time() + (int)\York\Configuration::$AUTH_BAN_TIME);
                self::setPersist(\York\Configuration::$AUTH_FAILED_LOG_INS, 0);

                \York\Database\Accessor\Factory::getUpdateObject('user', $result->id)
                    ->set('password', md5(\York\Helper\Password::generatePassword()))
                    ->update();
            }
        }
    }

    /**
     * creates auth session data
     *
     * @param \York\Database\FetchResult $result
     */
    public static function login($result = null)
    {
        /**
         * proposal for new login mechanism
         * try to log in, throw exceptions if needed!
         */

        $model = new \York\Database\Model();
        $dataObject = \York\Dependency\Manager::getRequestData();

        /**
         * get the user from the database if result does not provide a database result
         * get the id
         */
        if (null === $result) {
            $key = self::get(\York\Configuration::$AUTH_CREDENTIAL_USER_ID);
            $value = $dataObject->get($key);
            $result = $model->findOne('user', $value, $key);
        }

        /**
         * is a user found with this credentials?
         */
        if (null === $result) {
            self::checkBanAfterFailedLogin($result);
            \York\Dependency\Manager::getSplashManager()->addText(\York\Dependency\Manager::getTranslator()->translate('Unbekannter Nutzer'));

            throw new \York\Exception\Redirect('/auth/login');
        }
        /**
         * well a user was found, is his access matching to the database one's
         */

        if ($result->password !== md5($dataObject->get(self::get(\York\Configuration::$AUTH_CREDENTIAL_USER_ACCESS)))) {
            self::checkBanAfterFailedLogin($result);
            \York\Dependency\Manager::getSplashManager()->addText(\York\Dependency\Manager::getTranslator()->translate('Falsches Passwort!'));

            throw new \York\Exception\Redirect('/auth/login');
        }

        if (\York\Configuration::$USER_STATUS_BANNED == $result->status) {
            throw new \York\Exception\Redirect('/error/banned');
        }

        if (\York\Configuration::$USER_STATUS_PENDING == $result->status) {
            throw new \York\Exception\Redirect('/error/pending');
        }

        unset($result->password);

        self::setPersist(\York\Configuration::$AUTH_LOGGED_IN, true);
        self::setUser($result);

        \York\Database\Accessor\Factory::getUpdateObject('user', $result->id)
            ->set('lastlog', \York\Helper\Date::getDate())
            ->update();
    }

    /**
     * setter for the user
     *
     * @param \York\Database\FetchResult $user
     */
    public static function setUser($user)
    {
        self::setPersist(\York\Configuration::$AUTH_USER, $user);
    }

    /**
     * bans a user the time to ban was set in the defines file by BAN_TIME
     */
    public static function ban()
    {
        self::setPersist(\York\Configuration::$AUTH_USER_BANNED, time() + \York\Configuration::$BAN_TIME);
    }

    /**
     * unbans a user
     */
    public static function unBan()
    {
        self::setPersist(\York\Configuration::$AUTH_USER_BANNED, time() - 1);
    }

    /**
     * checks if a user is banned
     *
     * @return boolean
     */
    public static function isBanned()
    {
        return self::getRemainingBanTime() > 0;
    }

    /**
     * returns the seconds the user will is banned
     *
     * @return integer
     */
    public static function getRemainingBanTime()
    {
        return self::getPersisted(\York\Configuration::$AUTH_USER_BANNED) - time();
    }

    /**
     * setter for the logged in state
     *
     * @param boolean $isLoggedIn
     */
    public static function setIsLoggedIn($isLoggedIn = true)
    {
        self::setPersist(\York\Configuration::$AUTH_LOGGED_IN, $isLoggedIn);
    }

    /**
     * checks if user is logged in
     *
     * @return boolean
     */
    public static function isLoggedIn()
    {
        return true == self::getPersisted(\York\Configuration::$AUTH_LOGGED_IN);
    }

    /**
     * checks if user has access to the level
     * eg requested page (admin) has level 3, if user has 2 it returns false
     * if requested page (home) has access level 0, if user has at least 1, it returns true
     *
     * @param integer $level
     *
     * @return boolean
     */
    public static function hasAccess($level)
    {
        if (false === self::isLoggedIn()) {
            return false;
        }
        return $level <= self::getUserType();
    }

    /**
     * returns the amount of failed logins of the current user
     *
     * @return integer
     */
    public static function getUserFailedLogins()
    {
        return self::getPersisted(\York\Configuration::$AUTH_FAILED_LOG_INS);
    }

    /**
     * increase the failed login counter
     */
    public static function  increaseFailedLogins()
    {
        self::setPersist(\York\Configuration::$AUTH_FAILED_LOG_INS, self::getPersisted(\York\Configuration::$AUTH_FAILED_LOG_INS) + 1);
        if (self::getUserFailedLogins() > 2) {
            self::ban();
        }
    }

    /**
     * shortcut for user getting
     *
     * @return \Application\Model\User
     *
     * @throws \York\Exception\Auth
     */
    public static function getUser()
    {
        if (false === self::isLoggedIn()) {
            throw new \York\Exception\Auth('User is not logged in! cannot return user\'s properties!!');
        }

        return self::getPersisted(\York\Configuration::$AUTH_USER);
    }

    /**
     * @return \Application\Model\User
     *
     * @throws \York\Exception\Auth
     */
    public static function getUserModel()
    {
        if (false === self::isLoggedIn()) {
            throw new \York\Exception\Auth('User is not logged in! cannot return user\'s properties!!');
        }

        $manager = new \Application\Model\Manager\User();

        return $manager->getById(self::getPersisted(\York\Configuration::$AUTH_USER)->id);
    }

    /**
     * returns the id of the current logged in user
     *
     * @return integer
     */
    public static function getUserId()
    {
        return (int)self::getUser()->id;
    }

    /**
     * returns the type of the currently logged in user
     *
     * @return integer
     */
    public static function getUserType()
    {

        return (int)self::getUser()->type;
    }

    /**
     * returns the nick name of the currently logged in user
     *
     * @return string
     */
    public static function getUserNick()
    {
        return self::getUser()->nick;
    }


    /**
     * returns the email of the currently logged in user
     *
     * @return string
     */
    public static function getUserEmail()
    {
        return self::getUser()->email;
    }

    /**
     * returns the status of the currently logged in user
     *
     * @return integer
     */
    public static function getUserStatus()
    {
        return (int)self::getUser()->status;
    }

    /**
     * returns the date of the last login of the currently logged in user
     *
     * @return \DateTime
     */
    public static function getUserLastLogin()
    {
        return self::getUser()->lastlog;
    }
}
