<?php
namespace York\Storage;
use York\Code\Singleton;
abstract class StorageAbstract extends Singleton{
	/**
	 * constructor
	 *
	 * @param array $data
	 */
	public function __construct($data = array()){
		$this->setData($data);
	}

	/**
	 * the stored data
	 *
	 * @var array
	 */
	protected $data;

	/**
	 * retrieve the hole data
	 * @return array
	 */
	public function getData(){
		return $this->data;
	}

	/**
	 * usually this is not needed, but sometimes, so overwrite this if you need!
	 */
	public function shutDown(){}

}
