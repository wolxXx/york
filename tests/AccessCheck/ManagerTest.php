<?php
class ManagerTest extends \PHPUnit_Framework_TestCase{
	/**
	 * @var \York\AccessCheck\Manager
	 */
	protected $manager;

	public function setUp(){
		parent::setUp();
		$this->manager = new \York\AccessCheck\Manager();
	}

	public function testClearRules(){

		$this->manager->clearRules();
		$this->assertEmpty($this->manager->getRules());
	}

	public function testSetUserLevel(){
		$this->manager->setUserLevel(2);
		$this->assertSame(2, $this->manager->getUserLevel());
	}

	public function testSetIsLoggedIn(){
		$this->manager->setUserIsLoggedIn(true);
		$this->assertTrue($this->manager->isUserLoggedIn());
	}

	public function testGeneral(){
		$this->manager->addRule(new \York\AccessCheck\Rule('*'));
		$this->assertTrue($this->manager->checkAccess('pewpew'));
		$this->assertFalse($this->manager->requiresAuth('pewpew'));
	}

	public function testRemoveRule(){
		$this->manager->addRule(new \York\AccessCheck\Rule('pewpew', true, 3));
		$this->manager->removeRule('pewpew');
		$this->assertFalse($this->manager->requiresAuth('pewpew'));
	}

	public function testHasRule(){
		$this->manager->addRule(new \York\AccessCheck\Rule('pewpew', true, 3));
		$this->assertTrue($this->manager->hasRuleForAction('pewpew'));
	}

	public function testCheckForLogin(){
		$this->manager->addRule(new \York\AccessCheck\Rule('pewpew', true, 3));
		$this->assertFalse($this->manager->checkAccess('pewpew'));
	}

	public function testCheckForLevel(){
		$this->manager->addRule(new \York\AccessCheck\Rule('pewpew', true, 3));
		$this->assertFalse($this->manager->checkAccess('pewpew'));
	}

	public function testCheckForLevelAndSuccess(){
		$actionName = 'pewpew';
		$this->manager->setUserIsLoggedIn(true);
		$this->manager->setUserLevel(3);
		$this->manager->addRule(new \York\AccessCheck\Rule($actionName, true, 3));
		$this->assertTrue($this->manager->checkAccess($actionName));
		$this->assertTrue($this->manager->requiresAuth($actionName));
	}

	public function testAccessDenied(){
		$actionName = 'pewpew';
		$this->manager->setUserIsLoggedIn(true);
		$this->manager->setUserLevel(2);
		$this->manager->addRule(new \York\AccessCheck\Rule($actionName, true, 3));
		$this->assertTrue($this->manager->requiresAuth($actionName));
		$this->assertFalse($this->manager->checkAccess($actionName));
	}

	public function testCheckForApocalypseException(){
		$this->setExpectedException('\York\Exception\Apocalypse');
		$this->manager->addRule(new \York\AccessCheck\Rule('foobar'));
		$this->assertTrue($this->manager->checkAccess('pewpew'));
	}
} 