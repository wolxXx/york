<?php
namespace York\Logger;

/**
 * log file writer
 * writes all log messages for the the given levels to the given file
 *
 * @package \York\Logger
 * @version $version$
 * @author wolxXx
 */
class File extends LoggerAbstract
{
    /**
     * @var \York\FileSystem\File $file
     */
    protected $file;

    /**
     * @return $this
     */
    public static function Factory()
    {
        return new self();
    }

    /**
     * set the path to the log file
     * if the parent directories not exist, try to create them
     * if the log file does not exist, try to touch it
     *
     * @param string $fileName
     *
     * @return $this
     */
    public function setFilePath($fileName)
    {
        $logPath = \York\Helper\Application::getProjectRoot() . 'log/';

        new \York\FileSystem\Directory($logPath, true);

        $this->file = new \York\FileSystem\File($logPath . $fileName, true);

        return $this;
    }

    /**
     * @inheritdoc
     */
    protected function logAction($message)
    {
        $message = $message . PHP_EOL . PHP_EOL;
        $file = fopen($this->file->getFullName(), "a+");
        fwrite($file, $message);
        fclose($file);

        return $this;
    }

    /**
     * @inheritdoc
     * @todo implement me!
     */
    public function validate()
    {
        return $this;
    }
}
