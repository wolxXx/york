<?php
/**
 * @codeCoverageIgnore
 */
class YorkConfigurationTest extends \PHPUnit_Framework_TestCase{
	public function testInstantiation(){
		$this->assertInstanceOf('\York\Configuration', new YorkConfigurationTestClass());
	}

	public function testConfigureHostFails(){
		$this->setExpectedException('\York\Exception\Apocalypse');
		$configuration = new YorkConfigurationTestClass();
		$configuration->configureHost();
	}

	public function testConfigureApplicationFails(){
		$this->setExpectedException('\York\Exception\Apocalypse');
		$configuration = new YorkConfigurationTestClass();
		$configuration->configureApplication();
	}

	public function testCheckConfig(){
		$configuration = new YorkConfigurationTestClass();
		$this->assertTrue($configuration->checkConfig());
	}

	public function testConstants(){
		$this->assertSame('Auth.activateUserBanning', YorkConfigurationTestClass::$AUTH_ACTIVATE_USER_BANNING);
		$this->assertSame('Auth.credentialsUserAccess', \York\Configuration::$AUTH_CREDENTIAL_USER_ACCESS);
		$this->assertSame('Auth.loggedIn', \York\Configuration::$AUTH_LOGGED_IN);
		$this->assertSame('Auth.user', \York\Configuration::$AUTH_USER);
		$this->assertSame('Auth.failedLogins', \York\Configuration::$AUTH_FAILED_LOG_INS);
		$this->assertSame('Auth.banned', \York\Configuration::$AUTH_USER_BANNED);
		$this->assertSame('Auth.userBanTime', \York\Configuration::$AUTH_BAN_TIME);
		$this->assertSame(1337, \York\Configuration::$BAN_TIME);
		$this->assertSame(0, \York\Configuration::$USER_STATUS_PENDING);
		$this->assertSame(1, \York\Configuration::$USER_STATUS_ACTIVATED);
		$this->assertSame(2, \York\Configuration::$USER_STATUS_BANNED);
		$this->assertSame(0, \York\Configuration::$USER_TYPE_USUAL);
		$this->assertSame(1, \York\Configuration::$USER_TYPE_EDITOR);
		$this->assertSame(2, \York\Configuration::$USER_TYPE_ADMIN);
	}
}

class YorkConfigurationTestClass extends \York\Configuration{

}
