<?php
namespace York\Logger;
/**
 * sends the log message as an email
 *
 * @author wolxXx
 * @version 3.0
 * @package York\Logger
 */
class Email extends LoggerAbstract{
	protected $reciever;
	protected $sender;

	public function __construct($reciever, $sender){
		$this->reciever = $reciever;
		$this->sender = $sender;
	}

	public function log($message){
		Helper::sendMail2(array(
			'mailto' => ADMIN_EMAIL,
			'subject' => 'mail logger',
			'text' => $message
		));
	}
}
