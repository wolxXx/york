<?php
use \York\Auth\Manager as Auth;
/**
 * @codeCoverageIgnore
 */
class MockUser extends \York\Database\FetchResult{
	public $id = 1337;
	public $nick = 'Linus Torvalds';
	public $email = 'god@linux.org';
	public $lastlog = '2009-04-24 18:00:00';
	public $type = 3;
	public $status = 2;
	public $password = 'e206a54e97690cce50cc872dd70ee896';

	public function getPassword(){
		return $this->password;
	}
}
/**
 * @codeCoverageIgnore
 */
class AuthTest extends  PHPUnit_Framework_TestCase{
	public function testIsUserPasswordOk(){
		$this->markTestSkipped('');
		$this->assertFalse(\York\Auth\Manager::isUserPasswordOk('blubb', new MockUser()));
		$user = new MockUser();
		$user->password = \York\Auth\Manager::hashPassword('foobar');
		$this->assertTrue(\York\Auth\Manager::isUserPasswordOk('foobar', $user));
	}

	public function testHashPassword(){
		$this->markTestSkipped('');
		$this->assertSame(md5('foobar'), Auth::hashPassword('foobar'));
	}

	public function testSaltingPassword(){
		$this->markTestSkipped('');
		$this->assertSame('foobar', Auth::saltPassword('foobar'));
	}

	public function testLogin(){
		$this->markTestSkipped('');
		Auth::logout();
		$_POST[\Application\Configuration\Application::$AUTH_CREDENTIAL_USER_ID] = 'god@linux.org';
		$_POST[\York\Configuration::$AUTH_CREDENTIAL_USER_ID] = 'linux';
		Auth::login(new MockUser());
	}

	public function testIsLoggedInAfterLogout(){
		$this->markTestSkipped('');
		Auth::logout();
		$this->assertEquals(false, Auth::isLoggedIn());
	}

	public function testIsLoggedInAfterManualLogin(){
		$this->markTestSkipped('');
		Auth::setIsLoggedIn();
		$this->assertEquals(true, Auth::isLoggedIn());
	}

	public function testSetUser(){
		$this->markTestSkipped('');
		Auth::setUser(new MockUser());
		Auth::setIsLoggedIn();
		$this->assertEquals('1337', Auth::getUserId());
		$this->assertEquals('Linus Torvalds', Auth::getUserNick());
		$this->assertEquals('god@linux.org', Auth::getUserEmail());
	}

	/**
	 * @expectedException Exception
	 */
	public function testGetUserIdWithoutLogin(){
		$this->markTestSkipped('');
		Auth::logout();
		$this->assertEquals('1337', Auth::getUserId());
	}

	/**
	 * @expectedException Exception
	 */
	public function testGetUserEmailWithoutLogin(){
		$this->markTestSkipped('');
		Auth::logout();
		$this->assertEquals('geht doch eh nicht', Auth::getUserEmail());
	}

	/**
	 * @expectedException Exception
	 */
	public function testGetUserNickWithoutLogin(){
		$this->markTestSkipped('');
		Auth::logout();
		$this->assertEquals('geht doch eh nicht', Auth::getUserNick());
	}

	/**
	 * @expectedException Exception
	 */
	public function testGetUserLastLoginWithoutLogin(){
		$this->markTestSkipped('');
		Auth::logout();
		$this->assertEquals('geht doch eh nicht', Auth::getUserLastLogin());
	}

	/**
	 * @expectedException Exception
	 */
	public function testGetUserTypeWithoutLogin(){
		$this->markTestSkipped('');
		Auth::logout();
		$this->assertEquals('geht doch eh nicht', Auth::getUserType());
	}

	/**
	 * @expectedException Exception
	 */
	public function testGetStatusTypeWithoutLogin(){
		$this->markTestSkipped('');
		Auth::logout();
		$this->assertEquals('geht doch eh nicht', Auth::getUserStatus());
	}

	public function testBan(){
		$this->markTestSkipped('');
		Auth::ban();
		$this->assertTrue(Auth::isBanned());
	}

	public function testUnban(){
		$this->markTestSkipped('');
		Auth::unban();
		$this->assertFalse(Auth::isBanned());
	}

	public function testHasAccessWithoutLogin(){
		$this->markTestSkipped('');
		Auth::logout();
		$this->assertFalse(Auth::hasAccess(3));
	}

	public function testHasAccessWithLoginButLowType(){
		$this->markTestSkipped('');
		Auth::logout();
		$user = new MockUser();
		$user->type = 0;
		Auth::setUser($user);
		Auth::setIsLoggedIn();
		$this->assertFalse(Auth::hasAccess(3));
	}

	public function testHasAccessWithLoginWithHigherType(){
		$this->markTestSkipped('');
		Auth::logout();
		Auth::setUser(new MockUser());
		Auth::setIsLoggedIn();
		$this->assertTrue(Auth::hasAccess(1));
	}

	public function testHasAccessWithLoginWithMuchHigherType(){
		$this->markTestSkipped('');
		Auth::logout();
		Auth::setUser(new MockUser());
		Auth::setIsLoggedIn();
		$this->assertTrue(Auth::hasAccess(2));
	}

	public function testUserStatusActivated(){
		$this->markTestSkipped('');
		Auth::logout();
		$user = new MockUser();
		$user->status = 1;
		Auth::setUser($user);
		Auth::setIsLoggedIn();
		$this->assertSame(Auth::getUserStatus(), 1);
	}

	public function testUserStatusBanned(){
		$this->markTestSkipped('');
		Auth::logout();
		$user = new MockUser();
		$user->status = 2;
		Auth::setUser($user);
		Auth::setIsLoggedIn();
		$this->assertSame(Auth::getUserStatus(), 2);
	}

	public function testUserLastLogin(){
		$this->markTestSkipped('');
		Auth::logout();
		$user = new MockUser();
		$user->status = 1;
		Auth::setUser($user);
		Auth::setIsLoggedIn();
		$this->assertSame(Auth::getUserLastLogin(), '2009-04-24 18:00:00');
	}

	public function testFailedLoginsAfterLogout(){
		$this->markTestSkipped('');
		Auth::logout();
		$this->assertSame(Auth::getUserFailedLogins(), 0);
	}

	public function testFailedLogins(){
		$this->markTestSkipped('');
		$_POST[\York\Configuration::$AUTH_CREDENTIAL_USER_ID] = 'god@linux.org';
		$_POST[\York\Configuration::$AUTH_CREDENTIAL_USER_ACCESS] = 'linux';
		Auth::logout();
		$user = new MockUser();
		$user->password = 'hihi';
		Auth::login($user);
		$this->assertSame(Auth::getUserFailedLogins(), true === \York\Configuration::$AUTH_ACTIVATE_USER_BANNING? 1 : 0);
	}

	public function testMultiFailedLogins(){
		$this->markTestSkipped('');
		$_POST[\York\Configuration::$AUTH_CREDENTIAL_USER_ID] = 'god@linux.org';
		$_POST[\York\Configuration::$AUTH_CREDENTIAL_USER_ACCESS] = 'linux';
		Auth::logout();
		$user = new MockUser();
		$user->password = 'hihi';
		Auth::login($user);
		$user = new MockUser();
		$user->password = 'hihi';
		Auth::login($user);
		$this->assertSame(Auth::getUserFailedLogins(), true === Stack::getInstance()->get(ACTIVATEUSERBANNING)? 2 : 0);
	}

	public function testFailedLoginsToBeZeroForValidUser(){
		$this->markTestSkipped('');
		$_POST[\York\Configuration::$AUTH_CREDENTIAL_USER_ID] = 'god@linux.org';
		$_POST[\York\Configuration::$AUTH_CREDENTIAL_USER_ACCESS] = 'linux';
		Auth::logout();
		Auth::login(new MockUser());
		$this->assertSame(Auth::getUserFailedLogins(), 0);
	}

	public function testLoginForValidUser(){
		$this->markTestSkipped('');
		Auth::logout();
		$_POST[\York\Configuration::$AUTH_CREDENTIAL_USER_ID] = 'god@linux.org';
		$_POST[\York\Configuration::$AUTH_CREDENTIAL_USER_ACCESS] = 'linux';
		Auth::login(new MockUser());
		$this->assertTrue(Auth::isLoggedIn());
	}

}
