<?php
namespace York\Request;
/**
 * a wrapper for sent files
 * so the uploaded file is available as object
 * makes everything more comfortable
 *
 * @author wolxXx
 * @version 3.0
 * @package York\Request
 */
class File{
	/**
	 * temporary file path
	 * $tmp_name in array
	 *
	 * @var string
	 */
	public $tempName;

	/**
	 * the error number
	 *
	 * @var integer
	 */
	public $errorNumber;

	/**
	 * the error message
	 *
	 * @var string
	 */
	public $errorMessage;

	/**
	 * file type
	 *
	 * @var string
	 */
	public $type;

	/**
	 * the file extension
	 *
	 * @var string
	 */
	public $extension;

	/**
	 * indicator if the uploaded file is an image
	 *
	 * @var boolean
	 */
	public $isImage;

	/**
	 * the file size in byte
	 *
	 * @var integer
	 */
	public $size;


	/**
	 * human readable file size
	 *
	 * @var string
	 */
	public $sizeText;

	/**
	 * the file name
	 *
	 * @var string
	 */
	public $name;

	/**
	 * obfuscated, hashed file name
	 * so duplicated filenames are not occurring
	 *
	 * @var string
	 */
	public $newFileName;

	/**
	 * the index in the $_FILES array
	 * because maybe useful later
	 *
	 * @var string
	 */
	public $uploadIndex;

	/**
	 * if there was an error at upload
	 *
	 * @var boolean
	 */
	public $uploadSuccessful;

	/**
	 * constructor needs the usual file upload infos
	 * sets the is image flag
	 * grabs the extension
	 *
	 * @param string $name
	 * @param string $type
	 * @param string $tmp_name
	 * @param integer $error
	 * @param integer $size
	 * @param string $uploadIndex
	 */
	public function __construct($name, $type, $tmp_name, $error, $size, $uploadIndex){
		$this
			->setName($name)
			->setType($type)
			->setTempName($tmp_name)
			->setErrorNumber($error)
			->setFileSize($size)
			->setUploadIndex($uploadIndex)
			->setExtension()
			->setErrorMessage()
			->setSizeText()
			->setUploadSuccessful()
			->setNewFileName();
	}

	/**
	 * setter for the upload index
	 *
	 * @param string $index
	 * @return $this;
	 */
	public function setUploadIndex($index){
		$this->uploadIndex = $index;

		return $this;
	}

	/**
	 * setter for the file size
	 *
	 * @param integer $size
	 * @return $this;
	 */
	public function setFileSize($size){
		$this->size = $size;

		return $this;
	}

	/**
	 * setter for the error number
	 *
	 * @param integer $errorNumber
	 * @return $this;
	 */
	public function setErrorNumber($errorNumber){
		$this->errorNumber = $errorNumber;

		return $this;
	}

	/**
	 * setter for the name
	 *
	 * @param string $name
	 * @return $this;
	 */
	public function setName($name){
		$this->name = $name;

		return $this;
	}

	/**
	 * getter for the name
	 *
	 * @return string
	 */
	public function getName(){
		return $this->name;
	}

	/**
	 * setter for the file type
	 *
	 * @param string $type
	 * @return $this
	 */
	public function setType($type){
		$this->type = $type;

		return $this;
	}

	/**
	 * getter for the type
	 *
	 * @return string
	 */
	public function getType(){
		return $this->type;
	}

	/**
	 * setter for the temp name
	 *
	 * @param string $tempName
	 * @return $this
	 */
	public function setTempName($tempName){
		$this->tempName = $tempName;

		return $this;
	}

	/**
	 * getter for the temp name
	 *
	 * @return string
	 */
	public function getTempName(){
		return $this->tempName;
	}

	/**
	 * creates a new file name hashed with time and name
	 * makes sure that there will be no name conflicts
	 *
	 * @return \York\Request\File
	 */
	public function setNewFileName(){
		$this->newFileName = md5(time().$this->name.$this->sizeText.$this->size.$this->type).$this->extension;

		return $this;
	}

	/**
	 * getter for the new file name
	 *
	 * @return string
	 */
	public function getNewFileName(){
		return $this->newFileName;
	}

	/**
	 * checks if the errorNumber is zero
	 * calls therefore the core helper method
	 * can be overwritten for special interests
	 *
	 * @return $this;
	 */
	public function setUploadSuccessful(){
		$this->uploadSuccessful = 0 === $this->errorNumber;

		return $this;
	}

	/**
	 * checks if the file is an image
	 * calls therefore the core helper method
	 * can be overwritten for special interests
	 *
	 * @param $value
	 * @return $this
	 */
	public function setIsImage($value){
		$this->isImage = true === $value;

		return $this;
	}

	/**
	 * grabs the file extension from the filename
	 * calls therefore the core helper method
	 * can be overwritten for special interests
	 *
	 * @return $this
	 */
	public function setExtension(){
		$this->extension = \York\Helper\FileSystem::getFileExtension($this->name);

		return $this;
	}

	/**
	 * set the error message for the error number
	 * calls therefore the core helper method
	 * can be overwritten for special interests
	 *
	 * @return $this
	 */
	public function setErrorMessage(){
		$this->errorMessage = \York\Helper\Net::uploadErrorNumberToString($this->errorNumber);

		return $this;
	}

	/**
	 * sets the size test for the bytes
	 * calls therefore the core helper method
	 * can be overwritten for special interests
	 *
	 * @return $this
	 */
	public function setSizeText(){
		$this->sizeText = \York\Helper\FileSystem::fileSize($this->size);

		return $this;
	}

	/**
	 * check if the upload was successful
	 *
	 * @return boolean
	 */
	public function wasUploadSuccessful(){
		return $this->uploadSuccessful;
	}

	/**
	 * moves the file to a target
	 *
	 * @param string $target
	 * @return boolean
	 */
	public function move($target){
		if(false === is_dir(dirname($target))){
			mkdir(dirname($target), 0777, true);
		}

		return rename($this->tempName, $target);
	}

	/**
	 * check if this is an image
	 *
	 * @return boolean
	 */
	public function isImage(){
		return true === \York\Helper\FileSystem::isImage($this->getNewFileName()) || true === \York\Helper\FileSystem::isImage($this->getTempName());
	}

	/**
	 * check if this is an archive
	 * @return boolean
	 */
	public function isArchive(){
		return $this->isSupportedType(array(
			'application/x-7z-compressed',
			'application/x-rar',
			'application/rar',
			'application/x-tar',
			'application/gzip',
			'application/zip'
		));
	}

	/**
	 * check if this type is supported
	 *
	 * @param array $supported
	 * @return boolean
	 */
	public function isSupportedType(array $supported){
		return true === in_array($this->type, $supported);
	}
}
