<?php
namespace York\View;
use York\Storage\Application;
use York\Helper\Translator;

abstract class ItemAbstract extends Application implements ItemInterface{
	public function prepare(){
		return $this;
	}
}
