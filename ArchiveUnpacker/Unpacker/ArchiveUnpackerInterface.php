<?
/**
 * interface for archive unpackers
 *
 * @author wolxXx
 * @version 1.0
 * @package wolxXxMVC
 * @subpackage ArchiveUnpackers
 *
 */
interface ArchiveUnpackerInterface{
	/**
	 * unpacks the given file to the given target
	 *
	 * @param string $file
	 * @param string $target
	 */
	public function unpack($file, $target = null);
}