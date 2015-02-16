<?php
namespace Application\Configuration;

/**
 * default example configuration
 *
 * @package Application\Configuration
 * @version 1.0
 * @author York Framework
 *
 * @codeCoverageIgnore
 */
abstract class Application extends \York\Configuration
{
    public static $ADMIN_EMAIL = 'info@my-page.org';
    public static $APP_NAME = 'my-page.org';
    public static $APP_URL = 'my-page.org';
    public static $BAN_TIME = 1337;
    const dateFormat = 'd.m.Y H:i:s';
    const dateFormatShort = 'd.m.Y H:i';

    /**
     * @inheritdoc
     * @codeCoverageIgnore
     */
    public function configureApplication()
    {
        $hostname = isset($hostname) ? $hostname : php_uname("n");
        Dependency::getApplicationConfiguration()
            ->set('hostname', $hostname)
            ->set(\York\Configuration::$AUTH_ACTIVATE_USER_BANNING, true)
            ->set(\York\Configuration::$AUTH_BAN_TIME, 1337)
            ->set(\York\Configuration::$AUTH_CREDENTIAL_USER_ID, 'email')
            ->set(\York\Configuration::$AUTH_CREDENTIAL_USER_ACCESS, 'password')
            ->set('use_https', true)
            ->set('debug', true)
            ->set('admin_email', 'info@my-page.org')
            ->set('app_name', 'MyPage')
            ->set('app_url', 'my-page.org');

        Dependency::getLogger()
            ->addLogger(\York\Logger\File::Factory()->setFilePath('mail.log')->setLevel(\York\Logger\Level::EMAIL))
            ->addLogger(\York\Logger\File::Factory()->setFilePath('mail.err')->setLevel(\York\Logger\Level::EMAIL_FAILED))
            ->addLogger(\York\Logger\File::Factory()->setFilePath('general.log')->setLevel(\York\Logger\Level::ALL))
            ->addLogger(\York\Logger\File::Factory()->setFilePath('db.err')->setLevel(\York\Logger\Level::DATABASE_ERROR))
            ->addLogger(\York\Logger\File::Factory()->setFilePath('debug.log')->setLevel(\York\Logger\Level::DEBUG))
            ->addLogger(\York\Logger\File::Factory()->setFilePath('syscalls.log')->setLevel(\York\Logger\Level::CONSOLE_RUN))
            ->addLogger(\York\Logger\File::Factory()->setFilePath('db.debug')->setLevel(\York\Logger\Level::DATABASE_DEBUG));

        return $this;
    }
}
