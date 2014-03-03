<?php
/**
 * Created by PhpStorm.
 * User: wolxxx
 * Date: 10.12.13
 * Time: 08:15
 */

namespace York\FileSystem;


class Directory {
	protected $path;

	public function __construct($path, $createIfNotExists = false){
		$this->path = $path;
		$this->init(true === $createIfNotExists);
	}

	protected function init($createIfNotExists = false){
		if(false === is_dir($this->path)){
			if(false === $createIfNotExists){
				throw new \York\Exception\FileSystem(sprintf('given path %s is not a directory', $this->path));
			}
			try{
				mkdir($this->path, 0777, true);
			}catch (\York\Exception\York $exception){
				throw new \York\Exception\FileSystem(sprintf('cannot create directory %s', $this->path));
			}
		}

		if(false === is_readable($this->path)){
			throw new \York\Exception\FileSystem(sprintf('given directory %s is not readable', $this->path));
		}

		if(false === is_writable($this->path)){
			throw new \York\Exception\FileSystem(sprintf('given directory %s is not writable', $this->path));
		}
	}

	public function delete(){
		system('rm -rf '.$this->path);
		try{
			$this->init();
			return false;
		}catch (\York\Exception\FileSystem $exception){
			return true;
		}
	}
}
