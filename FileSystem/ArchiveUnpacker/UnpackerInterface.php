<?php
/**
 * Created by PhpStorm.
 * User: rene
 * Date: 27.01.14
 * Time: 14:42
 */

namespace York\FileSystem\ArchiveUnpacker;


interface UnpackerInterface {
	public function unpack();
	public function setSource();
	public function setTarget();
	public function getSource();
	public function getTarget();
} 