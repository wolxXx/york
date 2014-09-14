<?php
namespace York;
use York\Code\Factory;
use York\Code\FactoryInterface;
use York\Exception\Mailer as MailerException;
use York\FileSystem\File;
use York\Helper\Date;
use York\Logger\Manager;
use York\Storage\Application;
use York\Storage\Simple;
/**
 * mailer class for sending emails
 *
 * @author wolxXx
 * @version 3.0
 * @package York
 */
class Mailer implements MailerInterface{
	/**
	 * break character
	 *
	 * @var string
	 */
	protected $break = PHP_EOL;

	/**
	 * storage for receivers
	 *
	 * @var Storage\Simple
	 */
	protected $receivers;

	/**
	 * storage for carbon copy receivers
	 *
	 * @var Storage\Simple
	 */
	protected $carbonCopyReceivers;

	/**
	 * storage for blind carbon copy receivers
	 *
	 * @var Storage\Simple
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
	 * @var Storage\Simple
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
	 * @return Mailer
	 */
	public static function Factory(){
		return new self();
	}

	/**
	 * constructor
	 */
	public function __construct(){
		$this->init();
	}

	/**
	 * initialise all needed stuff
	 */
	protected function init(){
		$this->receivers = new Simple();
		$this->carbonCopyReceivers = new Simple();
		$this->blindCarbonCopyReceivers = new Simple();
		$this->sender = '';
		$this->subject = '';
		$this->text = '';
		$this->encoding = 'UTF-8';
		$this->files = new Simple();
	}

	/**
	 * @inheritdoc
	 */
	public function addReceiver($receiver){
		if(false === $this->hasReceiver($receiver)){
			$this->receivers->set($receiver, $receiver);
		}

		return $this;
	}

	/**
	 * @inheritdoc
	 */
	public function addReceivers(array $receivers){
		foreach($receivers as $current){
			$this->addReceiver($current);
		}

		return $this;
	}

	/**
	 * @inheritdoc
	 */
	public function clearReceivers(){
		$this->receivers->clear();

		return $this;
	}

	/**
	 * @inheritdoc
	 */
	public function getReceivers(){
		return array_keys($this->receivers->getAll());
	}

	/**
	 * @inheritdoc
	 */
	public function hasReceiver($receiver){
		return $this->receivers->hasDataForKey($receiver);
	}

	/**
	 * @inheritdoc
	 */
	public function removeReceiver($receiver){
		$this->receivers->remove($receiver);
		return $this;
	}

	/**
	 * @inheritdoc
	 */
	public function addCarbonCopyReceiver($receiver){
		if(false === $this->carbonCopyReceivers->hasDataForKey($receiver)){
			$this->carbonCopyReceivers->set($receiver, $receiver);
		}

		return $this;
	}

	/**
	 * @inheritdoc
	 */
	public function addCarbonCopyReceivers(array $receivers){
		foreach($receivers as $current){
			$this->addCarbonCopyReceiver($current);
		}

		return $this;
	}

	/**
	 * @inheritdoc
	 */
	public function clearCarbonCopyReceivers(){
		$this->carbonCopyReceivers->clear();

		return $this;
	}

	/**
	 * @inheritdoc
	 */
	public function getCarbonCopyReceivers(){
		return array_keys($this->carbonCopyReceivers->getAll());
	}

	/**
	 * @inheritdoc
	 */
	public function hasCarbonCopyReceiver($receiver){
		return $this->carbonCopyReceivers->hasDataForKey($receiver);
	}

	/**
	 * @inheritdoc
	 */
	public function removeCarbonCopyReceiver($receiver){
		$this->carbonCopyReceivers->remove($receiver);

		return $this;
	}

	/**
	 * @inheritdoc
	 */
	public function addBlindCarbonCopyReceiver($receiver){
		if(false === $this->blindCarbonCopyReceivers->hasDataForKey($receiver)){
			$this->blindCarbonCopyReceivers->set($receiver, $receiver);
		}

		return $this;
	}

	/**
	 * @inheritdoc
	 */
	public function addBlindCarbonCopyReceivers(array $receivers){
		foreach($receivers as $current){
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
	public function getBlindCarbonCopyReceivers(){
		return array_keys($this->blindCarbonCopyReceivers->getAll());
	}

	/**
	 * @inheritdoc
	 */
	public function hasBlindCarbonCopyReceiver($receiver){
		return $this->blindCarbonCopyReceivers->hasDataForKey($receiver);
	}

	/**
	 * @inheritdoc
	 */
	public function removeBlindCarbonCopyReceiver($receiver){
		$this->blindCarbonCopyReceivers->remove($receiver);
		return $this;
	}

	/**
	 * @inheritdoc
	 */
	public function addFile(File $file){
		if(false === $this->files->hasDataForKey($file->getFullName())){
			$this->files->set($file->getFullName(), $file);
		}

		return $this;
	}

	/**
	 * @inheritdoc
	 */
	public function addFiles(array $files){
		foreach($files as $current){
			$this->addFile($current);
		}

		return $this;
	}

	/**
	 * @inheritdoc
	 */
	public function hasFile(File $file){
		return $this->files->hasDataForKey($file->getFullName());
	}

	/**
	 * @inheritdoc
	 */
	public function removeFile(File $file){
		$this->files->removeKey($file->getFullName());

		return $this;
	}

	/**
	 * @inheritdoc
	 */
	public function clearFiles(){
		$this->files->clear();

		return $this;
	}

	/**
	 * @inheritdoc
	 */
	public function getSubject(){
		return $this->subject;
	}

	/**
	 * @inheritdoc
	 */
	public function setSubject($subject){
		$this->subject = $subject;

		return $this;
	}

	/**
	 * @inheritdoc
	 */
	public function getText(){
		return $this->text;
	}

	/**
	 * @inheritdoc
	 */
	public function setText($text){
		$this->text = $text;

		return $this;
	}

	/**
	 * @inheritdoc
	 */
	public function getEncoding(){
		return $this->encoding;
	}

	/**
	 * @inheritdoc
	 */
	public function setEncoding($encoding){
		$this->encoding = $encoding;

		return $this;
	}

	/**
	 * @inheritdoc
	 */
	public function getSender(){
		return $this->sender;
	}

	/**
	 * @inheritdoc
	 */
	public function setSender($sender){
		$this->sender = $sender;

		return $this;
	}

	/**
	 * @inheritdoc
	 */
	public function getFiles(){
		return $this->files->getAll();
	}

	/**
	 * log the mail
	 *
	 * @param $text
	 * @return \York\Mailer
	 */
	protected function log($text){
		$text .= sprintf('%s___________________________%s', $this->break, $this->break);
		\York\Dependency\Manager::get('logger')->log($text, Manager::LEVEL_EMAIL);
		return $this;
	}

	/**
	 * check if everything is fine fine fine :)
	 *
	 * @throws Exception\Mailer
	 */
	protected function check(){
		$subject = $this->getSubject();
		$receivers = $this->getReceivers();
		$text = $this->getText();
		if(true === empty($subject)){
			throw new MailerException('you need to set a subject');
		}

		if(true === empty($receivers)){
			throw new MailerException('you need to set at least one receiver');
		}

		if(true === empty($text)){
			throw new MailerException('you need to set a text');
		}
	}

	/**
	 * @inheritdoc
	 */
	public function send($force = false){

		$this->check();

		/**
		 * @var \York\Storage\Application
		 */
		$configuration = \York\Dependency\Manager::get('applicationConfiguration');

		$mode = $configuration->getSafely('mode', 'development');
		$sending = false;

		if(true === $force || 'production' === $mode){
			$sending = true;
		}

		if('' === $this->getSender()){
			$this->setSender($configuration->get('admin_email'));
		}

		$break = $this->break;

		$text = sprintf('%s%s___________________________%s%s', $break, $break, $break, $break);

		if(false === $sending){
			$text .= sprintf('DUMMY! NOT SENDING THIS!%s', $break);
		}

		$text .= sprintf('date: %s%s', Date::getDate(), $break);
		$text .= sprintf('sender: %s%s',$this->getSender(), $break);
		$text .= sprintf('subject: %s%s', $this->getSubject(), $break);
		$text .= sprintf('files: %s', $break);
		$files = $this->getFiles();
		if(0 === sizeof($files)){
			$text .= '-none-';
		}else{
			//@todo really send some files!!!!
			foreach($files as $current){
				$text .= sprintf('%s%s', $current->getFullName(), $break);
			}
		}
		$text .= PHP_EOL;
		$text .= sprintf('text: %s%s%s', $break, $this->getText(). $break, $break);



		if(false === $sending){
			$text .= sprintf('receivers: %s%s', implode(', ', $this->receivers->getAll()), $break);
			return $this->log($text);
		}

		//yes, really send this fucking email out to the nasty fucking shit receivers!
		$headers = array();
		$headers[] = "MIME-Version: 1.0";
		$headers[] = sprintf("Content-type: text/plain; charset=%s", $this->getEncoding());
		$headers[] = sprintf("From: %s", $this->getSender());
		$headers[] = sprintf("Reply-To: %s", $this->getSender());
		$headers[] = "X-Mailer: PHP/".phpversion();
		$headers[] = "";
		$headers = implode($break, $headers);
		$text .= sprintf('headers: %s%s', $headers, $break);

		$textSave = $text;

		foreach($this->getReceivers() as $receiver){
			$text = $textSave;
			$text .= sprintf('receiver: %s%s', $receiver, $break);
			$subject = '=?UTF-8?B?'.base64_encode($this->getSubject()).'?=';
			$result = mail($receiver, $subject, $this->getText(), $headers);
			$text .= sprintf('result: %s%s', true === $result? 'sent': 'NOT SENT', $break);
			$this->log($text);
		}
		return $this;
	}
}
