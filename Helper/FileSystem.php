<?php
namespace York\Helper;
/**
 * file helper utilities class
 *
 * @author wolxXx
 * @version 3.0
 * @package York\Helper
 */
class FileSystem{
	/**
	 * grabs the last chars from a file name
	 *
	 * @param string $fileName
	 * @param boolean $prependPoint
	 * @return string
	 */
	public static function getFileExtension($fileName, $prependPoint = true){
		$path = explode('.', $fileName);
		return strtolower((true === $prependPoint? '.': '').$path[sizeof($path)-1]);
	}

	/**
	 * checks if file type returns an image as content type
	 * $fileName should be the full path
	 *
	 * @param string $filename
	 * @return boolean
	 */
	public static function isImage($filename){
		return false !== strstr(self::getFileType($filename), 'image/');
	}

	/**
	 * returns the file name
	 * @param string $path
	 * @return string
	 */
	public static function getFileName($path){
		return basename(realpath($path));
	}

	/**
	 *
	 * checks the mime content type of the fileName
	 * $fileName requires a fullpath
	 * @param string $filename
	 * @return null|string
	 */
	public static function getFileType($filename){
		if(false === is_file($filename)){
			return null;
		}
		return mime_content_type($filename);
	}

	/**
	 * scans a directory for files
	 *
	 * @param string $path
	 * @param boolean $recursive
	 * @param array $exclude
	 * @param boolean $filesOnly
	 * @return array
	 */
	public static function scanDirectory($path, $recursive = false, $exclude = array(), $filesOnly = true){
		if(false === is_dir($path)){
			\York\Logger\Manager::getInstance()->log(sprintf('cannot scan %s as it is not a readable directory', $path), \York\Logger\Manager::TYPE_ALL, \York\Logger\Manager::LEVEL_DEBUG);
			return array();
		}
		$excludeDefault = array(
			'.',
			'..',
			'.svn',
			'.project',
			'tests',
			'tmp',
			'log'
		);
		if(true === is_array($exclude)){
			$exclude = array_merge($excludeDefault, $exclude);
		}elseif(true === is_string($exclude)){
			$excludeDefault[] = $exclude;
			$exclude = $excludeDefault;
		}else{
			$exclude = $excludeDefault;
		}
		$return = array();

		$path = '/' === $path[strlen($path) - 1]? $path : $path.'/';

		if(false === $recursive){
			/**
			 * @var \DirectoryIterator $fileInfo
			 */
			foreach (new \DirectoryIterator($path) as $fileInfo) {
				if(
					true === $fileInfo->isDot() ||
					true === $fileInfo->isDir() ||
					true === in_array(basename($fileInfo->getFilename()), $exclude)
				){
					continue;
				}
				$return[] = $path.$fileInfo->getFilename();
			}
		}else{
			$mode = true === $filesOnly? \RecursiveIteratorIterator::LEAVES_ONLY : \RecursiveIteratorIterator::SELF_FIRST;
			$files =  new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($path), \RecursiveIteratorIterator::LEAVES_ONLY);
			foreach(array_keys(iterator_to_array($files,true)) as $current){
				if(true === in_array(basename($current), $exclude)){
					continue;
				}
				$return[] = $current;
			}
		}

		return $return;
	}

	/**
	 * converts filesize to the smallest unit (b,kb,mb,gb)
	 *
	 * @param integer $size
	 * @return string
	 */
	public static function fileSize($size){
		if($size < 1024){
			return $size .' B';
		}
		if($size < 1024 * 1024){
			$size = ceil($size / 1024);
			return $size .' KB';
		}
		if($size < 1024 * 1024 * 1024){
			$size = ceil($size / (1024 * 1024));
			return $size .' MB';
		}
		if($size < 1024 * 1024 * 1024 * 1024){
			$size = ceil($size / (1024 * 1024 * 1024));
			return $size .' GB';
		}
		return $size;
	}
} 