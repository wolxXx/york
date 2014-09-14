<?php
namespace York\Logger;
/**
 * log file writer
 * writes all log messages for the the given levels to the given file
 *
 * @author wolxXx
 * @version 3.1\York\Template\Parser::DELIMITER;
 * @package York\Logger
 */
class File extends LoggerAbstract{
	/**
	 * @var string
	 */
	protected $filePath;

	/**
	 * @var \York\FileSystem\File $file
	 */
	protected $file;

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
	 * @throws \York\Exception\FileSystem
	 */
	public function setFilePath($filePath){
		$logPath = \York\Helper\Application::getProjectRoot().'log/';
		new \York\FileSystem\Directory($logPath, true);
		$this->file = new \York\FileSystem\File($logPath.basename($filePath), true);

		return $this;
	}

	/**
	 * @inheritdoc
	 */
	public function log($message){
		$message = $message.PHP_EOL.PHP_EOL;
		$file = fopen($this->file->getFullName(), "a+");
		fwrite($file, $message);
		fclose($file);

		return $this;
	}

}
