<?php
namespace York\Code;
/**
 * a singleton class with empty shutdown function
 *
 * @package York\Code
 */
abstract class SingletonWithoutShutdown extends Singleton{
	/**
	 * no shutdown you need, young padawan
	 *
	 * @return Singleton
	 */
	public function shutDown(){
		return $this;
	}
}
