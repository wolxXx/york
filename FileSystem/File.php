<?php
namespace York\FileSystem;
/**
 * abstraction for a file
 *
 * @author wolxXx
 * @version 3.0
 * @package York\FileSystem
 */
class File{
	/***
	 * @var string
	 */
	protected $path;

	/**
	 * @param string $path
	 * @param boolean $createIfNotExists
	 */
	public final function __construct($path, $createIfNotExists = false){
		$this->path = $path;
		$this->init(true === $createIfNotExists);
	}

	/**
	 * @param boolean $createIfNotExists
	 * @throws \York\Exception\FileSystem
	 */
	protected function init($createIfNotExists){
		if(false === file_exists($this->path)){
			if(false === $createIfNotExists){
				throw new \York\Exception\FileSystem(sprintf('given file %s does not exist', $this->path));
			}
			try{
				touch($this->path);
				chmod($this->path, 0774);
			}catch (\York\Exception\General $exception){
				throw new \York\Exception\FileSystem(sprintf('cannot touch file %s', $this->path));
			}
		}

		if(false === is_readable($this->path)){
			throw new \York\Exception\FileSystem(sprintf('given file %s is not readable', $this->path));
		}

		if(false === is_writable($this->path)){
			throw new \York\Exception\FileSystem(sprintf('given file %s is not writable', $this->path));
		}
	}

	/**
	 * @return string
	 */
	public function getContent(){
		return file_get_contents($this->getFullName());
	}

	/**
	 * @return string
	 */
	public function getType(){
		return \York\Helper\FileSystem::getFileType($this->path);
	}

	/**
	 * @return string
	 */
	public function getFullName(){
		return $this->path;
	}

}
