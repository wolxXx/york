<?php
namespace York\FileSystem\ArchiveUnpacker;

/**
 * Class Configuration
 *
 * @package York\FileSystem\ArchiveUnpacker
 * @version $version$
 * @author wolxXx
 */
class Configuration
{
    /***
     * @var \York\FileSystem\Directory
     */
    protected $target;

    /**
     * @var \York\FileSystem\File
     */
    protected $source;

    /**
     * @var \York\Type\Boolean
     */
    protected $preserveDirectories;

    /**
     * @param \York\FileSystem\File         $source
     * @param \York\FileSystem\Directory    $target
     * @param \York\Type\Boolean            $preserverDirectories
     */
    public function __construct(\York\FileSystem\File $source, \York\FileSystem\Directory $target, \York\Type\Boolean $preserverDirectories = null)
    {
        $this
            ->setSource($source)
            ->setTarget($target)
            ->setPreserveDirectories(null !== $preserverDirectories ? $preserverDirectories : new \York\Type\Boolean(false));
    }

    /**
     * @param \York\FileSystem\File         $source
     * @param \York\FileSystem\Directory    $target
     *
     * @return Configuration
     */
    public static function Factory(\York\FileSystem\File $source, \York\FileSystem\Directory $target)
    {
        return new static($source, $target, new \York\Type\Boolean(false));
    }

    /**
     * @return \York\Type\Boolean
     */
    public function getPreserveDirectories()
    {
        return $this->preserveDirectories;
    }

    /**
     * @param \York\Type\Boolean $preserveDirectories
     *
     * @return $this
     */
    public function setPreserveDirectories(\York\Type\Boolean $preserveDirectories)
    {
        $this->preserveDirectories = $preserveDirectories;

        return $this;
    }

    /**
     * @return \York\FileSystem\File
     */
    public function getSource()
    {
        return $this->source;
    }

    /**
     * @param \York\FileSystem\File $source
     *
     * @return $this
     */
    public function setSource(\York\FileSystem\File $source)
    {
        $this->source = $source;

        return $this;
    }

    /**
     * @return \York\FileSystem\Directory
     */
    public function getTarget()
    {
        return $this->target;
    }

    /**
     * @param \York\FileSystem\Directory $target
     *
     * @return $this
     */
    public function setTarget(\York\FileSystem\Directory $target)
    {
        $this->target = $target;

        return $this;
    }
}
