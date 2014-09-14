<?php
namespace Application\Migration;

class Migration%%number%% extends \York\Database\Migration{
	public function run(){
		/**
		 * this is where the main procedure takes place. drop your code here!
		 */
		$yourSQL = 'select * from foobar where 1 = 1';
		$this->query($yourSQL);
	}
}
