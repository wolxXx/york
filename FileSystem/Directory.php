<?php
namespace York\FileSystem;

/**
 * simple file system abstraction for directories
 *
 * @package York\FileSystem
 * @version $version$
 * @author wolxXx
 */
class Directory
{
    /**
     * the path to the directory
     * @var string
     */
    protected $path;

    /**
     * constructor
     * needs the path
     * creates the directory and all parents if wanted
     *
     * @param string    $path
     * @param boolean   $createIfNotExists
     */
    public function __construct($path, $createIfNotExists = false)
    {
        $this->path = \York\Helper\String::addTailingSlashIfNeeded($path);
        $this->init(true === $createIfNotExists);
    }

    /**
     * to string
     *
     * @return string
     */
    public function __toString()
    {
        return basename($this->path);
    }

    /**
     * retrieves the full path in the filesystem
     *
     * @return string
     */
    public function getFullPath()
    {
        return \York\Helper\String::addTailingSlashIfNeeded(realpath($this->path));
    }

    /**
     * get the name of the directory, usually the basename
     *
     * @return string
     */
    public function getName()
    {
        return basename($this->getFullPath());
    }

    /**
     * @return \York\FileSystem\Directory
     */
    public function getParent()
    {
        return new self($this->getFullPath() . '../');
    }

    /**
     * @return \York\FileSystem\Directory[] | \York\FileSystem\File[]
     */
    public function getChildren()
    {
        $result = array();
        $files = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($this->getFullPath()), \RecursiveIteratorIterator::SELF_FIRST);

        foreach (array_keys(iterator_to_array($files, true)) as $current) {
            if (true === in_array(basename($current), array('.', '..'))) {
                continue;
            }

            if (true === is_dir($current)) {
                $result[] = new Directory($current);

                continue;
            }

            $result[] = new File($current);
        }

        return $result;
    }

    /**
     * @return \York\FileSystem\File[]
     */
    public function getFiles()
    {
        $result = array();
        $files = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($this->getFullPath()), \RecursiveIteratorIterator::LEAVES_ONLY);

        foreach (array_keys(iterator_to_array($files, true)) as $current) {
            if (true === is_dir($current)) {
                continue;
            }

            $result[] = new File($current);
        }

        return $result;
    }

    /**
     * initialize
     *
     * @param boolean $createIfNotExists
     *
     * @throws \York\Exception\FileSystem
     */
    public function init($createIfNotExists = false)
    {
        if (false === $this->exists()) {
            if (false === $createIfNotExists) {
                throw new \York\Exception\FileSystem(sprintf('given path %s is not a directory', $this->path));
            }

            try {
                $this->create();

                if (false === $this->exists()) {
                    throw new \York\Exception\General();
                }
            } catch (\York\Exception\General $exception) {
                throw new \York\Exception\FileSystem(sprintf('cannot create directory %s', $this->path));
            }
        }

        if (false === $this->isReadable()) {
            throw new \York\Exception\FileSystem(sprintf('given directory %s is not readable', $this->path));
        }

        if (false === $this->isWritable()) {
            throw new \York\Exception\FileSystem(sprintf('given directory %s is not writable', $this->path));
        }
    }

    /**
     * delete the directory
     *
     * @return boolean
     */
    public function delete()
    {
        \York\Console\SystemCall::Factory(sprintf('rm -rf %s', $this->path))->run();
        try {
            $this->init();

            return false;
        } catch (\York\Exception\FileSystem $exception) {
            return true;
        }
    }

    /**
     * @return boolean
     */
    public function isReadable()
    {
        return is_readable($this->path);
    }

    /**
     * @return boolean
     */
    public function isWritable()
    {
        return is_writable($this->path);
    }

    /**
     * @return boolean
     */
    public function exists()
    {
        return is_dir($this->path);
    }

    /**
     * @return $this
     */
    protected function create()
    {
        mkdir($this->path, 0774, true);

        return $this;
    }
}
