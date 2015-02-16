<?php
namespace York;

/**
 * interface for the mailer
 *
 * @package \York
 * @version $version$
 * @author wolxXx
 */
interface MailerInterface
{
    /**
     * adds a receiver
     *
     * @param string $receiver
     *
     * @return $this
     */
    public function addReceiver($receiver);

    /**
     * adds multiple receivers
     *
     * @param string[] $receivers
     *
     * @return $this
     */
    public function addReceivers(array $receivers);

    /**
     * clears all receivers
     *
     * @return $this
     */
    public function clearReceivers();

    /**
     * retrieves all set receivers
     *
     * @return string[]
     */
    public function getReceivers();

    /**
     * overwrite the receivers
     *
     * @param string[] $receivers
     *
     * @return $this
     */
    public function setReceivers(array $receivers);

    /**
     * overwrite the receiver
     *
     * @param string $receiver
     *
     * @return $this
     */
    public function setReceiver($receiver);

    /**
     * checks if the given receiver was set before
     *
     * @param string $receiver
     *
     * @return boolean
     */
    public function hasReceiver($receiver);

    /**
     * removes a receiver
     *
     * @param string $receiver
     *
     * @return $this
     */
    public function removeReceiver($receiver);

    /**
     * adds a carbon copy receiver
     *
     * @param string $receiver
     *
     * @return $this
     */
    public function addCarbonCopyReceiver($receiver);

    /**
     * adds multiple carbon copy receivers
     *
     * @param string[] $receivers
     *
     * @return $this
     */
    public function addCarbonCopyReceivers(array $receivers);

    /**
     * clears all carbon copy receivers
     *
     * @return $this
     */
    public function clearCarbonCopyReceivers();

    /**
     * retrieves all set carbon copy receivers
     *
     * @return string[]
     */
    public function getCarbonCopyReceivers();

    /**
     * overwrite the carbon copy receivers
     *
     * @param string[] $receivers
     *
     * @return $this
     */
    public function setCarbonCopyReceivers(array $receivers);

    /**
     * overwrite the carbon copy receivers
     *
     * @param string $receiver
     *
     * @return $this
     */
    public function setCarbonCopyReceiver($receiver);

    /**
     * checks if the given carbon copy receiver was set before
     *
     * @param string $receiver
     *
     * @return boolean
     */
    public function hasCarbonCopyReceiver($receiver);

    /**
     * removes a carbon copy receiver
     *
     * @param string $receiver
     *
     * @return $this
     */
    public function removeCarbonCopyReceiver($receiver);

    /**
     * adds a blind carbon copy receiver
     *
     * @param string $receiver
     *
     * @return $this
     */
    public function addBlindCarbonCopyReceiver($receiver);

    /**
     * adds multiple blind carbon copy receivers
     *
     * @param string[] $receivers
     *
     * @return $this
     */
    public function addBlindCarbonCopyReceivers(array $receivers);

    /**
     * clears all blind carbon copy receivers
     *
     * @return $this
     */
    public function clearBlindCarbonCopyReceivers();

    /**
     * retrieves all set blind carbon copy receivers
     *
     * @return string[]
     */
    public function getBlindCarbonCopyReceivers();

    /**
     * overwrite the blind carbon copy receivers
     *
     * @param string[] $receivers
     *
     * @return $this
     */
    public function setBlindCarbonCopyReceivers(array $receivers);

    /**
     * overwrite the blind carbon copy receivers
     *
     * @param string $receiver
     *
     * @return $this
     */
    public function setBlindCarbonCopyReceiver($receiver);

    /**
     * checks if the given blind carbon copy receiver was set before
     *
     * @param string $receiver
     *
     * @return boolean
     */
    public function hasBlindCarbonCopyReceiver($receiver);

    /**
     * removes a blind carbon copy receiver
     *
     * @param string $receiver
     *
     * @return $this
     */
    public function removeBlindCarbonCopyReceiver($receiver);

    /**
     * adds a file to the files
     *
     * @param \York\FileSystem\File $file
     *
     * @return $this
     */
    public function addFile(\York\FileSystem\File $file);

    /**
     * adds multiple files
     *
     * @param \York\FileSystem\File[] $files
     *
     * @return $this
     */
    public function addFiles(array $files);

    /**
     * checks if the given file was already added
     *
     * @param \York\FileSystem\File $file
     *
     * @return boolean
     */
    public function hasFile(\York\FileSystem\File $file);

    /**
     * removes the given file from the list of files
     *
     * @param \York\FileSystem\File $file
     *
     * @return $this
     */
    public function removeFile(\York\FileSystem\File $file);

    /**
     * getter for all files
     *
     * @return \York\FileSystem\File[]
     */
    public function getFiles();

    /**
     * overwrites files
     *
     * @param \York\FileSystem\File[] $files
     *
     * @return $this
     */
    public function setFiles(array $files);

    /**
     * overwrites files
     *
     * @param \York\FileSystem\File $file
     *
     * @return $this
     */
    public function setFile($file);

    /**
     * clears all set files
     *
     * @return $this
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
     *
     * @return $this
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
     * @param string $text
     *
     * @return $this
     */
    public function setText($text);

    /**
     * add text
     *
     * @param string $text
     *
     * @return $this
     */
    public function addText($text);

    /**
     * getter for the sender
     *
     * @return string
     */
    public function getSender();

    /**
     * @param string $sender
     *
     * @return $this
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
     *
     * @return $this
     */
    public function setEncoding($encoding);

    /**
     * sends the mail
     *
     * @param boolean $force
     *
     * @return $this
     */
    public function send($force = false);
}
