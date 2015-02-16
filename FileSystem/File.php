<?php
namespace York\FileSystem;

/**
 * abstraction for a file
 *
 * @package York\FileSystem
 * @version $version$
 * @author wolxXx
 */
class File
{
    /***
     * @var string
     */
    protected $path;

    /**
     * @param string    $path
     * @param boolean   $createIfNotExists
     */
    public final function __construct($path, $createIfNotExists = false)
    {
        $this->path = $path;
        $this->init(true === $createIfNotExists);
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->getName();
    }

    /**
     * @return string
     */
    public function getName()
    {
        return basename($this->getFullName());
    }

    /**
     * @param string    $path
     * @param boolean   $createIfNotExists
     *
     * @return \York\FileSystem\File
     */
    public static final function Factory($path, $createIfNotExists = false)
    {
        return new self($path, $createIfNotExists);
    }

    /**
     * @param boolean $createIfNotExists
     *
     * @throws \York\Exception\FileSystem
     */
    protected function init($createIfNotExists)
    {
        if (false === file_exists($this->path)) {
            if (false === $createIfNotExists) {
                throw new \York\Exception\FileSystem(sprintf('given file %s does not exist', $this->path));
            }

            try {
                //create the parent directory, if needed, do it recursively
                $parent = str_replace(basename($this->path), '', $this->path);
                new Directory($parent, true);

                touch($this->path);
                chmod($this->path, 0774);
            } catch (\York\Exception\General $exception) {
                throw new \York\Exception\FileSystem(sprintf('cannot touch file %s', $this->path));
            }
        }

        if (false === is_readable($this->path)) {
            throw new \York\Exception\FileSystem(sprintf('given file %s is not readable', $this->path));
        }

        if (false === is_writable($this->path)) {
            throw new \York\Exception\FileSystem(sprintf('given file %s is not writable', $this->path));
        }
    }

    /**
     * @param string $target
     *
     * @return $this
     */
    public function move($target)
    {
        rename($this->getFullName(), $target);
        $this->path = $target;

        return $this;
    }

    /**
     * @param string $target
     *
     * @return $this
     */
    public function copy($target)
    {
        new self($target, true);
        copy($this->getFullName(), $target);

        return $this;
    }

    /**
     * @return boolean
     */
    public function delete()
    {
        unlink($this->getFullName());

        return file_exists($this->getFullName());
    }

    /**
     * @return string
     */
    public function getContent()
    {
        return file_get_contents($this->getFullName());
    }

    /**
     * @return string
     */
    public function getType()
    {
        return \York\Helper\FileSystem::getFileType($this->path);
    }

    /***
     * @return boolean
     */
    public function isImage()
    {
        return false !== strstr($this->getType(), 'image/');
    }

    /**
     * check if this is an archive
     *
     * @return boolean
     */
    public function isArchive()
    {
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
     * @param string[] $supported
     *
     * @return boolean
     */
    public function isSupportedType(array $supported)
    {
        return true === in_array($this->getType(), $supported);
    }

    /**
     * @return string
     */
    public function getFullName()
    {
        return $this->path;
    }

    /**
     * @return string
     */
    public function getPath()
    {
        return realpath($this->path);
    }
}
