<?php
namespace York\FileSystem\ArchiveUnpacker;
/**
 * Class Unpacker
 *
 * @package York\FileSystem\ArchiveUnpacker
 * @author wolxXx
 * @version 3.1
 */
abstract class Unpacker implements UnpackerInterface{
	/**
	 * @var \York\FileSystem\ArchiveUnpacker\Configuration
	 */
	protected $configuration;

	/**
	 * @inheritdoc
	 */
	public final function __construct(\York\FileSystem\ArchiveUnpacker\Configuration $configuration){
		$this->configuration = $configuration;
	}

	/**
	 * @inheritdoc
	 */
	public abstract function unpack();
}
