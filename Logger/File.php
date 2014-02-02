<?php
namespace York\Logger;
use York\Exception\FileSystem;

/**
 * log file writer
 * writes all log messages for the the given levels to the given file
 *
 * @author wolxXx
 * @version 3.0
 * @package York\Logger
 */
class File extends LoggerAbstract{
	/**
	 * @var string
	 */
	protected $filePath;

	/**
	 * set up the logger
	 *
	 * @param string $filePath
	 * @param string $level
	 */
	public function __construct($filePath, $level = Manager::LEVEL_ALL){
		$this
			->setFilePath($filePath)
			->setLevel($level);
	}

	/**
	 * set the path to the log file
	 * if the parent directories not exist, try to create them
	 * if the log file does not exist, try to touch it
	 *
	 * @param $filePath
	 * @return $this
	 * @throws FileSystem
	 * @todo use filesystem helpers!
	 */
	public function setFilePath($filePath){
		$this->filePath = $filePath;
		$path = explode(DIRECTORY_SEPARATOR, $this->filePath);
		array_pop($path);
		$path = implode(DIRECTORY_SEPARATOR, $path).DIRECTORY_SEPARATOR;
		if(false === file_exists($this->filePath)){
			if(false === is_dir($path)){
				try{
					mkdir($path, 0770, true);
				}catch (\Exception $exception){
					throw new FileSystem();
				}
			}
			try{
				touch($filePath);
			}catch (\Exception $exception){
				throw new FileSystem();
			}

		}
		return $this;
	}

	/**
	 * @inheritdoc
	 */
	public function log($message){
		$file = fopen('log/'.$this->filePath, 'a+');
		fputs($file, $message.PHP_EOL.PHP_EOL);
		fclose($file);
		return $this;
	}

}
