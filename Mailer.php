<?php
namespace York;

/**
 * mailer class for sending emails
 *
 * @package \York
 * @version $version$
 * @author wolxXx
 */
class Mailer implements \York\MailerInterface
{
    /**
     * break character
     *
     * @var string
     */
    protected $break = PHP_EOL;

    /**
     * storage for receivers
     *
     * @var \York\Storage\Simple
     */
    protected $receivers;

    /**
     * storage for carbon copy receivers
     *
     * @var \York\Storage\Simple
     */
    protected $carbonCopyReceivers;

    /**
     * storage for blind carbon copy receivers
     *
     * @var \York\Storage\Simple
     */
    protected $blindCarbonCopyReceivers;

    /**
     * the sender's email
     *
     * @var string
     */
    protected $sender;

    /**
     * the subject
     *
     * @var string
     */
    protected $subject;

    /**
     * storage for the files
     *
     * @var \York\Storage\Simple
     */
    protected $files;

    /**
     * the text
     *
     * @var string
     */
    protected $text;

    /**
     * the encoding
     *
     * @var string
     */
    protected $encoding;

    /**
     * factory
     *
     * @return \York\Mailer
     */
    public static function Factory()
    {
        return new static();
    }

    /**
     * constructor
     */
    public function __construct()
    {
        $this->init();
    }

    /**
     * initialise all needed stuff
     *
     * @return $this
     */
    protected function init()
    {
        $this->receivers = new \York\Storage\Simple();
        $this->carbonCopyReceivers = new \York\Storage\Simple();
        $this->blindCarbonCopyReceivers = new \York\Storage\Simple();
        $this->files = new \York\Storage\Simple();
        $this->sender = '';
        $this->subject = '';
        $this->text = '';
        $this->encoding = 'UTF-8';

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function addReceiver($receiver)
    {
        if (false === $this->hasReceiver($receiver)) {
            $this->receivers->set($receiver, $receiver);
        }

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function addReceivers(array $receivers)
    {
        foreach ($receivers as $current) {
            $this->addReceiver($current);
        }

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function clearReceivers()
    {
        $this->receivers->clear();

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function getReceivers()
    {
        return array_keys($this->receivers->getAll());
    }

    /**
     * @inheritdoc
     */
    public function setReceivers(array $receivers)
    {
        $this->clearReceivers();

        foreach ($receivers as $receiver) {
            $this->addReceiver($receiver);
        }

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function setReceiver($receiver)
    {
        return $this
            ->clearReceivers()
            ->addReceiver($receiver);
    }

    /**
     * @inheritdoc
     */
    public function hasReceiver($receiver)
    {
        return $this->receivers->hasDataForKey($receiver);
    }

    /**
     * @inheritdoc
     */
    public function removeReceiver($receiver)
    {
        $this->receivers->remove($receiver);

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function addCarbonCopyReceiver($receiver)
    {
        if (false === $this->carbonCopyReceivers->hasDataForKey($receiver)) {
            $this->carbonCopyReceivers->set($receiver, $receiver);
        }

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function addCarbonCopyReceivers(array $receivers)
    {
        foreach ($receivers as $current) {
            $this->addCarbonCopyReceiver($current);
        }

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function clearCarbonCopyReceivers()
    {
        $this->carbonCopyReceivers->clear();

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function getCarbonCopyReceivers()
    {
        return array_keys($this->carbonCopyReceivers->getAll());
    }

    /**
     * @inheritdoc
     */
    public function setCarbonCopyReceivers(array $receivers)
    {
        return $this
            ->clearCarbonCopyReceivers()
            ->addCarbonCopyReceivers($receivers);
    }

    /**
     * @inheritdoc
     */
    public function setCarbonCopyReceiver($receiver)
    {
        return $this
            ->clearCarbonCopyReceivers()
            ->addCarbonCopyReceiver($receiver);
    }

    /**
     * @inheritdoc
     */
    public function hasCarbonCopyReceiver($receiver)
    {
        return $this->carbonCopyReceivers->hasDataForKey($receiver);
    }

    /**
     * @inheritdoc
     */
    public function removeCarbonCopyReceiver($receiver)
    {
        $this->carbonCopyReceivers->remove($receiver);

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function addBlindCarbonCopyReceiver($receiver)
    {
        if (false === $this->blindCarbonCopyReceivers->hasDataForKey($receiver)) {
            $this->blindCarbonCopyReceivers->set($receiver, $receiver);
        }

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function addBlindCarbonCopyReceivers(array $receivers)
    {
        foreach ($receivers as $current) {
            $this->addBlindCarbonCopyReceiver($current);
        }

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function clearBlindCarbonCopyReceivers()
    {
        $this->blindCarbonCopyReceivers->clear();

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function getBlindCarbonCopyReceivers()
    {
        return array_keys($this->blindCarbonCopyReceivers->getAll());
    }

    /**
     * @inheritdoc
     */
    public function setBlindCarbonCopyReceivers(array $receivers)
    {
        return $this
            ->clearBlindCarbonCopyReceivers()
            ->addBlindCarbonCopyReceivers($receivers);
    }

    /**
     * @inheritdoc
     */
    public function setBlindCarbonCopyReceiver($receiver)
    {
        return $this
            ->clearBlindCarbonCopyReceivers()
            ->addBlindCarbonCopyReceiver($receiver);
    }

    /**
     * @inheritdoc
     */
    public function hasBlindCarbonCopyReceiver($receiver)
    {
        return $this->blindCarbonCopyReceivers->hasDataForKey($receiver);
    }

    /**
     * @inheritdoc
     */
    public function removeBlindCarbonCopyReceiver($receiver)
    {
        $this->blindCarbonCopyReceivers->remove($receiver);

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function addFile(\York\FileSystem\File $file)
    {
        if (false === $this->files->hasDataForKey($file->getFullName())) {
            $this->files->set($file->getFullName(), $file);
        }

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function addFiles(array $files)
    {
        foreach ($files as $current) {
            $this->addFile($current);
        }

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function hasFile(\York\FileSystem\File $file)
    {
        return $this->files->hasDataForKey($file->getFullName());
    }

    /**
     * @inheritdoc
     */
    public function removeFile(\York\FileSystem\File $file)
    {
        $this->files->removeKey($file->getFullName());

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function clearFiles()
    {
        $this->files->clear();

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function getSubject()
    {
        return $this->subject;
    }

    /**
     * @inheritdoc
     */
    public function setSubject($subject)
    {
        $this->subject = $subject;

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function getText()
    {
        return $this->text;
    }

    /**
     * @inheritdoc
     */
    public function setText($text)
    {
        $this->text = $text;

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function addText($text)
    {
        $this->text .= $text;

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function getEncoding()
    {
        return $this->encoding;
    }

    /**
     * @inheritdoc
     */
    public function setEncoding($encoding)
    {
        $this->encoding = $encoding;

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function getSender()
    {
        return $this->sender;
    }

    /**
     * @inheritdoc
     */
    public function setSender($sender)
    {
        $this->sender = $sender;

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function getFiles()
    {
        return $this->files->getAll();
    }

    /**
     * @inheritdoc
     */
    public function setFiles(array $files)
    {
        return $this
            ->clearFiles()
            ->addFiles($files);
    }

    /**
     * @inheritdoc
     */
    public function setFile($file)
    {
        return $this
            ->clearFiles()
            ->addFile($file);
    }

    /**
     * log the mail
     *
     * @param string $text
     *
     * @return $this
     */
    protected function log($text)
    {
        $text .= sprintf('%s___________________________%s', $this->break, $this->break);
        \York\Dependency\Manager::getLogger()->log($text, \York\Logger\Level::EMAIL);

        return $this;
    }

    /**
     * check if everything is fine fine fine :)
     *
     * @throws \York\Exception\Mailer
     */
    protected function check()
    {
        $subject = $this->getSubject();
        $receivers = $this->getReceivers();
        $text = $this->getText();

        if (true === empty($subject)) {
            throw new \York\Exception\Mailer('you need to set a subject');
        }

        if (true === empty($receivers)) {
            throw new \York\Exception\Mailer('you need to set at least one receiver');
        }

        if (true === empty($text)) {
            throw new \York\Exception\Mailer('you need to set a text');
        }
    }

    /**
     * @inheritdoc
     */
    public function send($force = false)
    {
        $this->check();
        $configuration = \York\Dependency\Manager::getApplicationConfiguration();
        $mode = $configuration->getSafely('mode', 'development');
        $sending = false;

        if (true === $force || 'production' === $mode) {
            $sending = true;
        }

        if ('' === $this->getSender()) {
            $this->setSender($configuration->get('admin_email'));
        }

        $break = $this->break;

        $text = sprintf('%s%s___________________________%s%s', $break, $break, $break, $break);

        if (false === $sending) {
            $text .= sprintf('DUMMY! NOT SENDING THIS!%s', $break);
        }

        $text .= sprintf('date: %s%s', \York\Helper\Date::getDate(), $break);
        $text .= sprintf('sender: %s%s', $this->getSender(), $break);
        $text .= sprintf('subject: %s%s', $this->getSubject(), $break);
        $text .= sprintf('files: %s', $break);
        $files = $this->getFiles();

        if (0 === sizeof($files)) {
            $text .= '-none-';
        } else {
            //@todo really send some files!!!!
            foreach ($files as $current) {
                $text .= sprintf('%s%s', $current->getFullName(), $break);
            }
        }
        $text .= PHP_EOL;
        $text .= sprintf('text: %s%s%s', $break, $this->getText() . $break, $break);


        if (false === $sending) {
            $text .= sprintf('receivers: %s%s', implode(', ', $this->receivers->getAll()), $break);

            return $this->log($text);
        }

        $headers = array();
        $headers[] = "MIME-Version: 1.0";
        $headers[] = sprintf("Content-type: text/plain; charset=%s", $this->getEncoding());
        $headers[] = sprintf("From: %s", $this->getSender());
        $headers[] = sprintf("Reply-To: %s", $this->getSender());
        $headers[] = "X-Mailer: PHP/" . phpversion();
        $headers[] = "";
        $headers = implode($break, $headers);
        $text .= sprintf('headers: %s%s', $headers, $break);

        $textSave = $text;

        foreach ($this->getReceivers() as $receiver) {
            $text = $textSave;
            $text .= sprintf('receiver: %s%s', $receiver, $break);
            $subject = '=?UTF-8?B?' . base64_encode($this->getSubject()) . '?=';
            $result = mail($receiver, $subject, $this->getText(), $headers);
            $text .= sprintf('result: %s%s', true === $result ? 'sent' : 'NOT SENT', $break);
            $this->log($text);
        }

        return $this;
    }
}
