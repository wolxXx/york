<?php
/**
 * Created by PhpStorm.
 * User: rene
 * Date: 27.01.14
 * Time: 14:40
 */

namespace York\FileSystem\ArchiveUnpacker;


abstract class Unpacker {
	protected $source;
	protected $target;

	public function __construct($source, $target){
		$this->source = $source;
		$this->target = $target;
	}

	public function getSource(){
		return $this->source;
	}

	public function setSource($source){
		$this->source = $source;
		return $this;
	}

	public abstract function unpack();
} 