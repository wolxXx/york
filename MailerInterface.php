<?php
namespace York;
/**
 * interface for the mailer
 *
 * @author wolxXx
 * @version 3.0
 * @package York
 */
interface MailerInterface{
	/**
	 * adds a receiver
	 *
	 * @param string $receiver
	 * @return \York\MailerInterface
	 */
	public function addReceiver($receiver);

	/**
	 * adds multiple receivers
	 *
	 * @param string[] $receivers
	 * @return \York\MailerInterface
	 */
	public function addReceivers(array $receivers);

	/**
	 * clears all receivers
	 *
	 * @return \York\MailerInterface
	 */
	public function clearReceivers();

	/**
	 * retrieves all set receivers
	 *
	 * @return string[]
	 */
	public function getReceivers();

	/**
	 * checks if the given receiver was set before
	 *
	 * @param string $receiver
	 * @return boolean
	 */
	public function hasReceiver($receiver);

	/**
	 * removes a receiver
	 *
	 * @param string $receiver
	 * @return \York\MailerInterface
	 */
	public function removeReceiver($receiver);

	/**
	 * adds a carbon copy receiver
	 *
	 * @param string $receiver
	 * @return \York\MailerInterface
	 */
	public function addCarbonCopyReceiver($receiver);

	/**
	 * adds multiple carbon copy receivers
	 *
	 * @param string[] $receivers
	 * @return \York\MailerInterface
	 */
	public function addCarbonCopyReceivers(array $receivers);

	/**
	 * clears all carbon copy receivers
	 *
	 * @return \York\MailerInterface
	 */
	public function clearCarbonCopyReceivers();

	/**
	 * retrieves all set carbon copy receivers
	 *
	 * @return string[]
	 */
	public function getCarbonCopyReceivers();

	/**
	 * checks if the given carbon copy receiver was set before
	 *
	 * @param string $receiver
	 * @return boolean
	 */
	public function hasCarbonCopyReceiver($receiver);

	/**
	 * removes a carbon copy receiver
	 *
	 * @param string $receiver
	 * @return \York\MailerInterface
	 */
	public function removeCarbonCopyReceiver($receiver);

	/**
	 * adds a blind carbon copy receiver
	 *
	 * @param string $receiver
	 * @return \York\MailerInterface
	 */
	public function addBlindCarbonCopyReceiver($receiver);

	/**
	 * adds multiple blind carbon copy receivers
	 *
	 * @param string[] $receivers
	 * @return \York\MailerInterface
	 */
	public function addBlindCarbonCopyReceivers(array $receivers);

	/**
	 * clears all blind carbon copy receivers
	 *
	 * @return \York\MailerInterface
	 */
	public function clearBlindCarbonCopyReceivers();

	/**
	 * retrieves all set blind carbon copy receivers
	 *
	 * @return string[]
	 */
	public function getBlindCarbonCopyReceivers();

	/**
	 * checks if the given blind carbon copy receiver was set before
	 *
	 * @param string $receiver
	 * @return boolean
	 */
	public function hasBlindCarbonCopyReceiver($receiver);

	/**
	 * removes a blind carbon copy receiver
	 *
	 * @param string $receiver
	 * @return \York\MailerInterface
	 */
	public function removeBlindCarbonCopyReceiver($receiver);

	/**
	 * adds a file to the files
	 *
	 * @param \York\FileSystem\File $file
	 * @return \York\MailerInterface
	 */
	public function addFile(\York\FileSystem\File $file);

	/**
	 * adds multiple files
	 *
	 * @param \York\FileSystem\File[] $files
	 * @return \York\MailerInterface
	 */
	public function addFiles(array $files);

	/**
	 * checks if the given file was already added
	 *
	 * @param \York\FileSystem\File $file
	 * @return boolean
	 */
	public function hasFile(\York\FileSystem\File $file);

	/**
	 * removes the given file from the list of files
	 *
	 * @param \York\FileSystem\File $file
	 * @return \York\MailerInterface
	 */
	public function removeFile(\York\FileSystem\File $file);

	/**
	 * getter for all files
	 *
	 * @return \York\FileSystem\File[]
	 */
	public function getFiles();

	/**
	 * clears all set files
	 *
	 * @return \York\MailerInterface
	 */
	public function clearFiles();

	/**
	 * getter for the subject
	 *
	 * @return string
	 */
	public function getSubject();

	/**
	 * setter for the subject
	 *
	 * @param $subject
	 * @return \York\MailerInterface
	 */
	public function setSubject($subject);

	/**
	 * getter for the text
	 *
	 * @return string
	 */
	public function getText();

	/**
	 * setter for the text
	 *
	 * @param $text
	 * @return \York\MailerInterface
	 */
	public function setText($text);

	/**
	 * getter for the sender
	 *
	 * @return string
	 */
	public function getSender();

	/**
	 * @param string $sender
	 * @return \York\MailerInterface
	 */
	public function setSender($sender);

	/**
	 * getter for the encoding
	 *
	 * @return string
	 */
	public function getEncoding();

	/**
	 * setter for the encoding
	 *
	 * @param $encoding
	 * @return \York\MailerInterface
	 */
	public function setEncoding($encoding);

	/**
	 * sends the mail
	 *
	 * @return \York\MailerInterface
	 */
	public function send($force = false);
}
