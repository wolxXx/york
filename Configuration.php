<?php
namespace York;

/**
 * config base for having objects as config
 *
 * @package \York
 * @version $version$
 * @author wolxXx
 */
abstract class Configuration
{
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
    public static $USER_STATUS_ACTIVATED = 1;
    public static $USER_STATUS_BANNED = 2;
    public static $USER_TYPE_USUAL = 0;
    public static $USER_TYPE_EDITOR = 1;
    public static $USER_TYPE_ADMIN = 2;

    /**
     * an instance of the stack
     *
     * @var \York\Storage\Application
     */
    protected $stack;

    /**
     * get an instance of the stack
     */
    public final function __construct()
    {
        $this->stack = \York\Dependency\Manager::getApplicationConfiguration()
            ->set('output.isVerboseEnabled', false)
            ->set('output.isDebugEnabled', false)
            ->set('output.isStandardEnabled', true);
    }

    /**
     * configuration of the application
     *
     * @throws \York\Exception\Apocalypse
     */
    public function configureApplication()
    {
        throw new \York\Exception\Apocalypse('please configure application');
    }

    /**
     * configuration of the host
     * place database credentials here
     * configure whatever you want
     *
     * @throws \York\Exception\Apocalypse
     */
    public function configureHost()
    {
        throw new \York\Exception\Apocalypse('please configure host');
    }

    /**
     * checks if the minimal needed settings are done
     * @todo specify them :)
     */
    public final function checkConfig()
    {
        return true;
    }
}
