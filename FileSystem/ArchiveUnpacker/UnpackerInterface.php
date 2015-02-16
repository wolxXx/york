<?php
namespace York\FileSystem\ArchiveUnpacker;
/**
 * interface for all unpackers
 *
 * @package York\FileSystem\ArchiveUnpacker
 * @version $version$
 * @author wolxXx
 */
interface UnpackerInterface
{
    /**
     * @param \York\FileSystem\ArchiveUnpacker\Configuration $configuration
     */
    public function __construct(\York\FileSystem\ArchiveUnpacker\Configuration $configuration);

    /**
     * @return \York\Type\Boolean
     */
    public function unpack();
}
