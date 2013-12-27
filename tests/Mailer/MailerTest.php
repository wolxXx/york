<?php
class MailerTest extends \PHPUnit_Framework_TestCase{

	protected $email1 = 'linus.torvals@linux.org';
	protected $email2 = 'devops@wolxXx.de';
	protected $email3 = 'bill.gates@fuckyoumicrosoft.hell';
	protected $receivers = array();

	public function setUp(){
		parent::setUp();

		$this->receivers = array(
			$this->email1,
			$this->email2,
			$this->email3
		);

		/**
		 * @var \York\Storage\Application $configuration
		 */
		$configuration = \York\Dependency\Manager::get('applicationConfiguration');
		$configuration->set('admin_email', 'devops@wolxXx.de');
		$configuration->set('mode', 'development');
	}


	/**
	 * @return \York\Mailer
	 */
	protected function getMailer(){
		/**
		 * @var \York\Mailer $mailer;
		 */
		return \York\Dependency\Manager::get('mailer')
			->clearBlindCarbonCopyReceivers()
			->clearCarbonCopyReceivers()
			->clearReceivers()
			->clearFiles();
	}



	public function testInstantiation(){
		$this->assertInstanceOf('\York\Mailer', $this->getMailer());
	}

	public function testClearAndAddReceiver(){
		$mailer = $this->getMailer();
		$mailer->clearReceivers();
		$this->assertEmpty($mailer->getReceivers());
		$mailer->addReceiver($this->email1);
		$this->assertSame(1, sizeof($mailer->getReceivers()));
		$this->assertContains($this->email1, $mailer->getReceivers());

		$mailer->addReceiver($this->email2);
		$this->assertSame(2, sizeof($mailer->getReceivers()));
		$this->assertContains($this->email2, $mailer->getReceivers());
	}

	public function testClearAndAddCarbonCopyReceivers(){
		$mailer = $this->getMailer();
		$mailer->clearReceivers();
		$mailer->clearCarbonCopyReceivers();
		$this->assertEmpty($mailer->getReceivers());
		$this->assertEmpty($mailer->getCarbonCopyReceivers());
		$mailer->addCarbonCopyReceiver($this->email1);
		$this->assertSame(0, sizeof($mailer->getReceivers()));
		$this->assertSame(1, sizeof($mailer->getCarbonCopyReceivers()));
		$this->assertContains($this->email1, $mailer->getCarbonCopyReceivers());

		$mailer->addCarbonCopyReceiver($this->email2);
		$this->assertSame(0, sizeof($mailer->getReceivers()));
		$this->assertSame(2, sizeof($mailer->getCarbonCopyReceivers()));
		$this->assertContains($this->email2, $mailer->getCarbonCopyReceivers());
	}

	public function testClearAndAddBlindCarbonCopyReceivers(){
		$mailer = $this->getMailer();
		$mailer->clearReceivers();
		$mailer->clearBlindCarbonCopyReceivers();
		$this->assertEmpty($mailer->getReceivers());
		$this->assertEmpty($mailer->getCarbonCopyReceivers());
		$mailer->addCarbonCopyReceiver($this->email1);
		$this->assertSame(0, sizeof($mailer->getReceivers()));
		$this->assertSame(1, sizeof($mailer->getCarbonCopyReceivers()));
		$this->assertContains($this->email1, $mailer->getCarbonCopyReceivers());

		$mailer->addCarbonCopyReceiver($this->email2);
		$this->assertSame(0, sizeof($mailer->getReceivers()));
		$this->assertSame(2, sizeof($mailer->getCarbonCopyReceivers()));
		$this->assertContains($this->email2, $mailer->getCarbonCopyReceivers());
	}

	public function testAddMultipleReceivers(){
		$mailer = $this->getMailer();
		$mailer
			->addReceivers($this->receivers)
			->addCarbonCopyReceivers($this->receivers)
			->addBlindCarbonCopyReceivers($this->receivers);

		$this->assertSame(3, sizeof($mailer->getReceivers()));
		$this->assertSame(3, sizeof($mailer->getCarbonCopyReceivers()));
		$this->assertSame(3, sizeof($mailer->getBlindCarbonCopyReceivers()));
	}

	public function testRemoveReceiver(){
		$mailer = $this->getMailer();
		$mailer
			->addReceiver($this->email1)
			->addCarbonCopyReceiver($this->email1)
			->addBlindCarbonCopyReceiver($this->email1);

		$this->assertTrue($mailer->hasReceiver($this->email1));
		$mailer->removeReceiver($this->email1);
		$this->assertFalse($mailer->hasReceiver($this->email1));

		$this->assertTrue($mailer->hasCarbonCopyReceiver($this->email1));
		$mailer->removeCarbonCopyReceiver($this->email1);
		$this->assertFalse($mailer->hasCarbonCopyReceiver($this->email1));

		$this->assertTrue($mailer->hasBlindCarbonCopyReceiver($this->email1));
		$mailer->removeBlindCarbonCopyReceiver($this->email1);
		$this->assertFalse($mailer->hasBlindCarbonCopyReceiver($this->email1));
	}

	public function testSetGetSubject(){
		$text = 'fooobar';
		$mailer = $this->getMailer();
		$this->assertSame('', $mailer->getSubject());
		$mailer->setSubject($text);
		$this->assertSame($text, $mailer->getSubject());
	}

	public function testSetGetText(){
		$text = 'fooobar';
		$mailer = $this->getMailer();
		$this->assertSame('', $mailer->getText());
		$mailer->setText($text);
		$this->assertSame($text, $mailer->getText());
	}

	public function testSetGetEncoding(){
		$encoding = 'fooobar';
		$mailer = $this->getMailer();
		$this->assertSame('UTF-8', $mailer->getEncoding());
		$mailer->setEncoding($encoding);
		$this->assertSame($encoding, $mailer->getEncoding());
	}

	public function testGetSetSender(){
		$sender = 'fooobar';
		$mailer = $this->getMailer();
		$this->assertSame('', $mailer->getSender());
		$mailer->setSender($sender);
		$this->assertSame($sender, $mailer->getSender());
	}

	public function testFiles(){
		$mailer = $this->getMailer();
		$file = new \York\FileSystem\File(__DIR__.'/fixtures/bar.txt');
		$this->assertEmpty($mailer->getFiles());
		$mailer->addFile($file);
		$this->assertTrue($mailer->hasFile($file));
		$mailer->removeFile($file);
		$this->assertFalse($mailer->hasFile($file));

		$mailer->clearFiles();
		$this->assertEmpty($mailer->getFiles());
		$mailer->addFiles(array($file));
		$this->assertTrue($mailer->hasFile($file));
		$this->assertSame(1, sizeof($mailer->getFiles()));
	}


	public function testSendButNoSubjectSet(){
		$this->setExpectedException('\York\Exception\Mailer');
		$this->getMailer()->send();
	}

	public function testSendButNoTextSet(){
		$this->setExpectedException('\York\Exception\Mailer');
		$this->getMailer()->setSubject('fooobaaar')->send();
	}

	public function testSendButNoReceiverSet(){
		$this->setExpectedException('\York\Exception\Mailer');
		$this->getMailer()->setSubject('fooobaaar')->addReceiver('fooooobaaaaar')->send();
	}

	public function testSendButNotSending(){
		$this->getMailer()->setSubject('fooobaaar')->addReceiver('fooooobaaaaar')->setText('lorl')->send(false);
	}

	public function testSend(){
		$mailer = $this->getMailer();
		$mailer->setText('fooobar')->setSubject('fooooobaaaaaar')->addReceiver('devops@wolxXx.de')->send();
	}

	public function testSendWithFiles(){
		$mailer = $this->getMailer();
		$file1 = new \York\FileSystem\File(__DIR__.'/fixtures/bar.txt');
		$file2 = new \York\FileSystem\File(__DIR__.'/fixtures/foo.txt');
		$mailer
			->setText('fooobar')
			->setSubject('fooooobaaaaaar')
			->addReceiver('devops@wolxXx.de')
			->addFile($file1)
			->addFile($file2)
			->send();
	}
}
