<?php
namespace York\Console;


abstract class Parameter{
	protected $isRequired;
	protected $default;
	protected $key;
	protected $value;

	public function __construct($key, $isRequired = false, $default = null){
		$this->key = $key;
		$this->setIsRequired($isRequired);
		$this->setDefault($default);
	}

	public function setValue($value){
		$this->value = $value;
		return $this;
	}

	public function getValue(){
		return $this->value;
	}

	public function setIsRequired($isRequired = false){
		$this->isRequired = true === $isRequired;

		return $this;
	}

	public function isRequired(){
		return true === $this->isRequired();
	}

	public function setDefault($default = null){
		$this->default = $default;

		return $this;
	}

	public function getDefault(){
		return $this->default;
	}

	public function getKey(){
		return $this->key;
	}
}
