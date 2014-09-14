<?php
namespace York\FileSystem\ArchiveUnpacker;
/**
 * Class Rar
 *
 * @package York\FileSystem\ArchiveUnpacker
 * @author wolxXx
 * @version 3.1
 */
class Rar extends Unpacker{
	/**
	 * @inheritdoc
	 */
	public function unpack(){
		$result = new \York\Type\Boolean(false);
		try{
			$result->set(true);
		}catch(\Exception $exception){

		}

		return $result;
	}
}
