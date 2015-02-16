<?php

/**
 * @codeCoverageIgnore
 */
class YorkMailerTest extends \PHPUnit_Framework_TestCase
{
    public function testInstantiation()
    {
        $this->assertInstanceOf('\York\Mailer', new \York\Mailer());
        $this->assertInstanceOf('\York\Mailer', \York\Mailer::Factory());
    }

    public function testAddGetSetReceivers()
    {
        $mailer = new \York\Mailer();
        $this->assertEmpty($mailer->getReceivers());
        $mailer->addReceiver('foobar');
        $this->assertSame(array('foobar'), $mailer->getReceivers());

        $mailer->addReceivers(array('foo', 'bar'));
        $this->assertSame(array('foobar', 'foo', 'bar'), $mailer->getReceivers());

        $this->assertTrue($mailer->hasReceiver('foobar'));
        $mailer->clearReceivers();

        $this->assertEmpty($mailer->getReceivers());
        $this->assertFalse($mailer->hasReceiver('foobar'));

        $mailer->addReceiver('foobar');
        $this->assertTrue($mailer->hasReceiver('foobar'));
        $mailer->removeReceiver('foobar');
        $this->assertFalse($mailer->hasReceiver('foobar'));

        $mailer->clearReceivers();
        $this->assertEmpty($mailer->getReceivers());

        $mailer->setReceiver('foobar');
        $this->assertTrue($mailer->hasReceiver('foobar'));
        $this->assertSame(1, sizeof($mailer->getReceivers()));
        $mailer->setReceiver('barfoo');
        $this->assertFalse($mailer->hasReceiver('foobar'));
        $this->assertTrue($mailer->hasReceiver('barfoo'));
        $this->assertSame(1, sizeof($mailer->getReceivers()));

        $mailer->setReceivers(array('foo', 'bar'));
        $this->assertTrue($mailer->hasReceiver('foo'));
        $this->assertTrue($mailer->hasReceiver('bar'));
        $this->assertSame(2, sizeof($mailer->getReceivers()));
    }

    public function testAddGetSetCarbonCopyReceivers()
    {
        $mailer = new \York\Mailer();
        $this->assertEmpty($mailer->getCarbonCopyReceivers());
        $mailer->addCarbonCopyReceiver('foobar');
        $this->assertSame(array('foobar'), $mailer->getCarbonCopyReceivers());

        $mailer->addCarbonCopyReceivers(array('foo', 'bar'));
        $this->assertSame(array('foobar', 'foo', 'bar'), $mailer->getCarbonCopyReceivers());

        $this->assertTrue($mailer->hasCarbonCopyReceiver('foobar'));
        $mailer->clearCarbonCopyReceivers();

        $this->assertEmpty($mailer->getCarbonCopyReceivers());
        $this->assertFalse($mailer->hasCarbonCopyReceiver('foobar'));

        $mailer->addCarbonCopyReceiver('foobar');
        $this->assertTrue($mailer->hasCarbonCopyReceiver('foobar'));
        $mailer->removeCarbonCopyReceiver('foobar');
        $this->assertFalse($mailer->hasCarbonCopyReceiver('foobar'));

        $mailer->clearCarbonCopyReceivers();
        $this->assertEmpty($mailer->getCarbonCopyReceivers());

        $mailer->setCarbonCopyReceiver('foobar');
        $this->assertTrue($mailer->hasCarbonCopyReceiver('foobar'));
        $this->assertSame(1, sizeof($mailer->getCarbonCopyReceivers()));
        $mailer->setCarbonCopyReceiver('barfoo');
        $this->assertFalse($mailer->hasCarbonCopyReceiver('foobar'));
        $this->assertTrue($mailer->hasCarbonCopyReceiver('barfoo'));
        $this->assertSame(1, sizeof($mailer->getCarbonCopyReceivers()));

        $mailer->setCarbonCopyReceivers(array('foo', 'bar'));
        $this->assertTrue($mailer->hasCarbonCopyReceiver('foo'));
        $this->assertTrue($mailer->hasCarbonCopyReceiver('bar'));
        $this->assertSame(2, sizeof($mailer->getCarbonCopyReceivers()));
    }

    public function testAddGetSetBlindCarbonCopyReceivers()
    {
        $mailer = new \York\Mailer();
        $this->assertEmpty($mailer->getBlindCarbonCopyReceivers());
        $mailer->addBlindCarbonCopyReceiver('foobar');
        $this->assertSame(array('foobar'), $mailer->getBlindCarbonCopyReceivers());

        $mailer->addBlindCarbonCopyReceivers(array('foo', 'bar'));
        $this->assertSame(array('foobar', 'foo', 'bar'), $mailer->getBlindCarbonCopyReceivers());

        $this->assertTrue($mailer->hasBlindCarbonCopyReceiver('foobar'));
        $mailer->clearBlindCarbonCopyReceivers();

        $this->assertEmpty($mailer->getBlindCarbonCopyReceivers());
        $this->assertFalse($mailer->hasBlindCarbonCopyReceiver('foobar'));

        $mailer->addBlindCarbonCopyReceiver('foobar');
        $this->assertTrue($mailer->hasBlindCarbonCopyReceiver('foobar'));
        $mailer->removeBlindCarbonCopyReceiver('foobar');
        $this->assertFalse($mailer->hasBlindCarbonCopyReceiver('foobar'));

        #
        $mailer->clearBlindCarbonCopyReceivers();
        $this->assertEmpty($mailer->getBlindCarbonCopyReceivers());

        $mailer->setBlindCarbonCopyReceiver('foobar');
        $this->assertTrue($mailer->hasBlindCarbonCopyReceiver('foobar'));
        $this->assertSame(1, sizeof($mailer->getBlindCarbonCopyReceivers()));
        $mailer->setBlindCarbonCopyReceiver('barfoo');
        $this->assertFalse($mailer->hasBlindCarbonCopyReceiver('foobar'));
        $this->assertTrue($mailer->hasBlindCarbonCopyReceiver('barfoo'));
        $this->assertSame(1, sizeof($mailer->getBlindCarbonCopyReceivers()));

        $mailer->setBlindCarbonCopyReceivers(array('foo', 'bar'));
        $this->assertTrue($mailer->hasBlindCarbonCopyReceiver('foo'));
        $this->assertTrue($mailer->hasBlindCarbonCopyReceiver('bar'));
        $this->assertSame(2, sizeof($mailer->getBlindCarbonCopyReceivers()));
    }

    public function testAddGetSetRemoveFiles()
    {
        $image1 = new \York\FileSystem\File(__DIR__ . '/fixtures/image1.jpg');
        $image2 = new \York\FileSystem\File(__DIR__ . '/fixtures/image2.jpg');
        $mailer = \York\Mailer::Factory();
        $mailer->addFile($image1);
        $this->assertSame(1, sizeof($mailer->getFiles()));
        $this->assertContains($image1, $mailer->getFiles());
        $this->assertTrue($mailer->hasFile($image1));

        $mailer->addFiles(array($image1, $image2));
        $this->assertSame(2, sizeof($mailer->getFiles()));
        $this->assertContains($image1, $mailer->getFiles());
        $this->assertContains($image2, $mailer->getFiles());
        $this->assertTrue($mailer->hasFile($image1));
        $this->assertTrue($mailer->hasFile($image2));

        $mailer->removeFile($image1);
        $this->assertSame(1, sizeof($mailer->getFiles()));
        $this->assertFalse($mailer->hasFile($image1));
        $this->assertTrue($mailer->hasFile($image2));

        $mailer->clearFiles();
        $this->assertSame(0, sizeof($mailer->getFiles()));
        $this->assertFalse($mailer->hasFile($image1));
        $this->assertFalse($mailer->hasFile($image2));

        $mailer->setFile($image1);
        $this->assertSame(1, sizeof($mailer->getFiles()));

        $mailer->setFiles(array($image1, $image2));
        $this->assertSame(2, sizeof($mailer->getFiles()));
    }

    public function testGetSetSubject()
    {
        $mailer = \York\Mailer::Factory();
        $this->assertSame('', $mailer->getSubject());
        $mailer->setSubject('fooooobaaaaaar');
        $this->assertSame('fooooobaaaaaar', $mailer->getSubject());
    }

    public function testGetSetAddText()
    {
        $mailer = \York\Mailer::Factory();
        $this->assertSame('', $mailer->getText());
        $mailer->setText('fooooobaaaaaar');
        $this->assertSame('fooooobaaaaaar', $mailer->getText());
        $mailer->setText('lol');
        $this->assertSame('lol', $mailer->getText());
        $mailer->addText('lol');
        $this->assertSame('lollol', $mailer->getText());
    }

    public function testGetSetEncoding()
    {
        $mailer = \York\Mailer::Factory();
        $this->assertSame('UTF-8', $mailer->getEncoding());
        $mailer->setEncoding('fooooobaaaaaar');
        $this->assertSame('fooooobaaaaaar', $mailer->getEncoding());
    }

    public function testGetSetSender()
    {
        $mailer = \York\Mailer::Factory();
        $this->assertSame('', $mailer->getSender());
        $mailer->setSender('fooooobaaaaaar');
        $this->assertSame('fooooobaaaaaar', $mailer->getSender());
    }

    public function testCheckWithoutSubjectFails()
    {
        $this->setExpectedException('\York\Exception\Mailer');
        \York\Mailer::Factory()->send();
    }

    public function testCheckWithoutReceiversFails()
    {
        $this->setExpectedException('\York\Exception\Mailer');
        \York\Mailer::Factory()->setSubject('foobar')->send();
    }

    public function testCheckWithoutTextFails()
    {
        $this->setExpectedException('\York\Exception\Mailer');
        \York\Mailer::Factory()->setSubject('foobar')->addReceiver('devops@wolxXx.de')->send();
    }

    public function testSend()
    {
        \York\Dependency\Manager::getApplicationConfiguration()->set('admin_email', 'devops@wolxXx.de');
        \York\Mailer::Factory()
            ->addFile(new \York\FileSystem\File(__DIR__ . '/fixtures/image1.jpg'))
            ->setSubject('York Unit Test Mail')
            ->addReceiver('devops@wolxXx.de')
            ->setText('York Unit Test Mail')
            ->send(true);

        \York\Mailer::Factory()
            ->setSubject('York Unit Test Mail')
            ->addReceiver('devops@wolxXx.de')
            ->setText('York Unit Test Mail')
            ->send(true);

        \York\Mailer::Factory()
            ->addFile(new \York\FileSystem\File(__DIR__ . '/fixtures/image1.jpg'))
            ->setSubject('York Unit Test Mail')
            ->addReceiver('devops@wolxXx.de')
            ->setText('York Unit Test Mail')
            ->send(false);
    }
}
