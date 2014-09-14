<?php
namespace York\FileSystem\ArchiveUnpacker;
/**
 * interface for all unpackers
 *
 * @package York\FileSystem\ArchiveUnpacker
 * @author wolxXx
 * @version 3.1
 */
interface UnpackerInterface {
	/**
	 * @param Configuration $configuration
	 */
	public function __construct(\York\FileSystem\ArchiveUnpacker\Configuration $configuration);

	/**
	 * @return \York\Type\Boolean
	 */
	public function unpack();
}
