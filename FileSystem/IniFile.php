<?php
namespace York\FileSystem;
/**
 * special class for ini files
 *
 * @author wolxXx
 * @version 3.1
 * @package York\FileSystem
 */
class IniFile extends File{
	/**
	 * @var array
	 */
	protected $content;

	/**
	 * @var boolean
	 */
	protected $parsed = false;

	/**
	 * @return array
	 */
	public function parse(){
		$this->content = parse_ini_file($this->getFullName(), true, 2);
		$this->parsed = true;

		return $this;
	}


	public function getContent(){
		if(false === $this->parsed){
			$this->parse();
		}

		return $this->content;
	}
}
