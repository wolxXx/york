<?php
namespace York;

/**
 * Requirement checking class
 *
 * @package \York
 * @version $version$
 * @author wolxXx
 */
class Requirement
{
    /**
     * @var string[]
     */
    protected $files = array(
        'Application/Configuration/Host.php',
        'Application/Configuration/Application.php',
        'Application/Configuration/Bootstrap.php'
    );

    /**
     * @var string[]
     */
    protected $directories = array(
        'log',
        'tmp'
    );

    /**
     * @var string[]
     */
    protected $messages = array();

    /***
     * @return $this
     */
    protected function checkFiles()
    {
        foreach ($this->files as $file) {
            try {
                new \York\FileSystem\File($file);
            } catch (\Exception $exception) {
                $this->messages[] = 'required file does not exist: ' . $file;
            }
        }

        return $this;
    }

    /**
     * @return $this
     */
    protected function checkDirectories()
    {
        foreach ($this->directories as $directory) {
            try {
                new \York\FileSystem\Directory($directory);
            } catch (\Exception $exception) {
                $this->messages[] = 'required directory does not exist: ' . $directory;
            }
        }

        return $this;
    }

    /**
     * @return $this
     *
     * @throws \York\Exception\Requirement
     */
    protected function checkMessages()
    {
        if (false === empty($this->messages)) {
            $this->messages = array_merge(array('some files and / or directories are missing. please fix.'), $this->messages);

            throw new \York\Exception\Requirement($this->messages);
        }

        return $this;
    }

    /**
     * @return $this
     */
    public static function Factory()
    {
        return new self();
    }

    /**
     * @return $this
     */
    public function check()
    {
        $this
            ->checkFiles()
            ->checkDirectories()
            ->checkMessages();

        return $this;
    }
}
