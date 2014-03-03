<?php
namespace York\Console;

/**
 * read line from console
 * filters can be applied, but must then return something...
 *
 * @author wolxXx
 * @version 3.0
 * @package York\Console
 */
class Readline {
	/**
	 * the prompt
	 *
	 * @var string
	 */
	protected $prompt;

	/**
	 * the user's input
	 *
	 * @var mixed
	 */
	protected $value;

	/**
	 * filters that are applied to the user's input
	 *
	 * @var callable[]
	 */
	protected $filters;

	/**
	 * setup
	 *
	 * @param string $prompt
	 */
	public function __construct($prompt){
		$this->prompt = $prompt;
		$this->filters = array();
	}

	/**
	 * shortcut factory
	 *
	 * @param string $prompt
	 * @return Readline
	 */
	public static function Factory($prompt){
		return new self($prompt);
	}

	/**
	 * add a filter function
	 *
	 * @param callable $filter
	 * @return $this
	 */
	public function addFilter($filter){
		$this->filters[] = $filter;
		return $this;
	}

	/**
	 * add several filter functions
	 *
	 * @param callable[] $filters
	 * @return $this
	 */
	public function addFilters(array $filters){
		foreach($filters as $filter){
			$this->addFilter($filter);
		}

		return $this;
	}

	/**
	 * clear all set filters
	 *
	 * @return $this
	 */
	public function clearFilters(){
		$this->filters = array();

		return $this;
	}

	/**
	 * read the value
	 *
	 * @return $this
	 */
	public function read(){
		$this->value = readline(sprintf('%s: ', $this->prompt));
		$this->applyFilters();

		return $this;
	}

	/**
	 * get the filtered user input
	 *
	 * @return mixed
	 */
	public function getValue(){
		return $this->value;
	}

	/**
	 * apply all set filters
	 *
	 * @return $this
	 */
	public function applyFilters(){
		foreach($this->filters as $filter){
			$this->value = $filter($this->value);
		}

		return $this;
	}
}
