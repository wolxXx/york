<?php
namespace York\Storage;
/**
 * Class StorageAbstract
 * @package York\Storage
 */
abstract class StorageAbstract{
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
