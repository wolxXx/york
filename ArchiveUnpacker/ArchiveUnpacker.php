<?
/**
 * class for unpacking archives
 *
 * @author wolxXx
 * @version 1.0
 * @package wolxXxMVC
 * @subpackage ArchiveUnpacker
 */
class ArchiveUnpacker{
	/**
	 * instance of the current unpacker
	 *
	 * @var ArchiveUnpackerInterface
	 */
	protected $instance;
	public static function Factory($file, $target){
		if(false === file_exists($file)){
			throw new ArchiveUnpackerException();
		}
	}
}