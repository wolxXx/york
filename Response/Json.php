<?php
namespace York\Response;
/**
 * default and standard json response
 *
 * @author wolxXx
 * @version 3.0
 * @package \York\Response
 */
class Json{
	/**
	 * http like status code
	 *
	 * @var integer
	 */
	 protected $status;

	/**
	 * if there was an error
	 *
	 * @var boolean
	 */
	protected $error;

	/**
	 * the transport data
	 *
	 * @var array
	 */
	protected $data;

	/**
	 * optional message
	 *
	 * @var string
	 */
	protected $message;

	/**
	 * @return $this
	 * @throws \York\Exception\UndefinedApiStatus
	 */
	public function init(){
		$apiCode = \York\Dependency\Manager::get('apiCode');
		return $this
			->setError(false)
			->setMessage(\York\Request\Api\Code::getStatusTextForCode($apiCode::OK))
			->setStatus($apiCode::OK)
			->clearData()
		;
	}

	/**
	 * factory
	 *
	 * @return $this
	 */
	public static function Factory(){
		$instance = new self();

		return $instance->init();
	}

	/**
	 * echoes the the generated json
	 *
	 * @return $this
	 */
	public function outputJSON(){
		echo $this->toJSON();

		return $this;
	}

	/**
	 * converts the class to a json object
	 *
	 * @return string
	 */
	public function toJSON(){
		$json = new \stdClass();
		foreach(array(
			'message',
			'status',
			'error',
			'data'
		) as $foo){
			$json->$foo = $this->$foo;
		}

		return json_encode($json);
	}

	/**
	 * sets the whole data array
	 *
	 * @param array $data
	 * @return $this
	 */
	public function setData($data = array()){
		$this->data = $data;

		return $this;
	}

	/**
	 * clears all data
	 *
	 * @return $this
	 */
	public function clearData(){
		$this->setData();

		return $this;
	}

	/**
	 * adds data to the data array
	 *
	 * @param string $key
	 * @param mixed $value
	 * @return $this
	 */
	public function addData($key, $value){
		$this->data[$key] = $value;

		return $this;
	}

	/**
	 * setter for the error status
	 *
	 * @param boolean $value
	 * @return $this
	 */
	public function setError($value){
		$this->error = true === $value? "true" : "false";

		return $this;
	}

	/**
	 * setter for the status code
	 * automatically sets the message with the status code string
	 *
	 * @param integer $status
	 * @return $this
	 */
	public function setStatusAndBelongingMessage($status){
		$this->setStatus($status);
		$apiCode = \York\Dependency\Manager::get('apiCode');
		$this->setMessage($apiCode::getStatusTextForCode($status));

		return $this;
	}

	/**
	 * sets the error flag to true
	 * sets the status and its message
	 *
	 * @param integer $status
	 * @return $this
	 */
	public function setStatusAndBelongingMessageForError($status){
		$this->setError(true);

		return $this->setStatusAndBelongingMessage($status);
	}

	/**
	 * setter for the status code
	 *
	 * @param integer $value
	 * @return $this
	 */
	public function setStatus($value){
		$this->status = intval($value).'';

		return $this;
	}

	/**
	 * setter for the message field
	 *
	 * @param string $value
	 * @return $this
	 */
	public function setMessage($value){
		$this->message = $value;

		return $this;
	}

	/**
	 * clears the message
	 *
	 * @return $this
	 */
	public function clearMessage(){

		return $this->setMessage('');
	}

	/**
	 *
	 * getter for the error flag
	 *
	 * @return boolean
	 */
	public function getError(){
		return $this->error;
	}

	/**
	 * getter for the message string
	 *
	 * @return string
	 */
	public function getMessage(){
		return $this->message;
	}

	/**
	 * getter for the status code
	 *
	 * @return integer
	 */
	public function getStatus(){
		return $this->status;
	}

	/**
	 * getter for the data
	 *
	 * @return array | \stdClass
	 */
	public function getData(){
		return $this->data;
	}
}
