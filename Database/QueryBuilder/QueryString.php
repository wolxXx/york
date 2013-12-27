<?php
namespace York\Database\QueryBuilder;
/**
 * just a simple query string class
 * implementing the QueryStringInterface
 *
 * @author wolxXx
 * @version 3.0
 * @package York\Database\QueryBuilder
 */
class QueryString implements \York\Database\QueryBuilder\QueryStringInterface{
	/**
	 * the query string
	 *
	 * @var string
	 */
	protected $queryString;

	/**
	 * constructor
	 *
	 * @param string $queryString
	 */
	public function __construct($queryString = null){
		$this->setQueryString($queryString);
	}

	/**
	 * (non-PHPdoc)
	 * @see QueryStringInterface::setQueryString()
	 */
	public function setQueryString($string){
		$this->queryString = $string;
		return $this;
	}

	/**
	 * (non-PHPdoc)
	 * @see QueryStringInterface::getQueryString()
	 */
	public function getQueryString($cleared = false){
		if(true === $cleared){
			$this->queryString = str_replace(array("\n", "\t"), array(' ', ''), $this->queryString);
			while(false !== strstr($this->queryString, '  ')){
				$this->queryString = str_replace('  ', ' ', $this->queryString);
			}
			$this->queryString = str_replace(' ;', ';', $this->queryString);
			$this->queryString = trim($this->queryString, ' ');
		}
		return $this->queryString;
	}
}