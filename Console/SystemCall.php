<?php
namespace York\Console;

/**
 * system call abstraction
 *
 * @author wolxXx
 * @version 3.0
 * @package York\Console
 */
class SystemCall {
	/**
	 * the command
	 *
	 * @var string
	 */
	protected $command;

	/**
	 * the arguments
	 *
	 * @var string[]
	 */
	protected $arguments;

	/**
	 * the result output
	 *
	 * @var string[]
	 */
	protected $result;

	/**
	 * flag for command has run
	 *
	 * @var boolean
	 */
	protected $hasRun = false;

	/**
	 * set up
	 *
	 * @param string $command
	 * @param string[] $arguments
	 */
	public function __construct($command, $arguments = array()){
		$this->command = $command;
		$this->arguments = $arguments;
	}

	/**
	 * factory function
	 *
	 * @param string $command
	 * @param array $arguments
	 * @return SystemCall
	 */
	public static function Factory($command, $arguments = array()){
		return new self($command, $arguments);
	}

	/**
	 * run the command
	 *
	 * @return SystemCall
	 */
	public function run(){
		exec(sprintf('%s %s', $this->command, implode(' ', $this->arguments)), $resultOutput, $result);
		$this->result = $resultOutput;
		$this->hasRun = true;

		return $this;
	}

	/**
	 * get the output
	 *
	 * @return \string[]
	 * @throws \York\Exception\SystemCall
	 */
	public function getOutput(){
		if(true !== $this->hasRun){
			throw new \York\Exception\SystemCall('you must run the call before getting the result!');
		}

		return $this->result;
	}
}
