<?php
namespace York\Database\QueryBuilder;
/**
 * select query builder
 * wraps a condition array to a select query string
 *
 * @author wolxXx
 * @version 3.0
 * @package York\Database\QueryBuilder
 */
class Select extends \York\Database\QueryBuilder{
	/**
	 * @inheritdoc
	 */
	protected function checkConditions(){
		if(true === empty($this->conditions)){
			throw new \York\Exception\QueryGenerator('empty conditions');
		}

		if(false === isset($this->conditions['from'])){
			throw new \York\Exception\QueryGenerator('no "from" section found!!');
		}
	}

	/**
	 * checks if the query was a query for selecting more than one element
	 * @return boolean
	 */
	public function isQueryForAll(){
		return 'all' === $this->conditions['method'];
	}

	/**
	 * (non-PHPdoc)
	 * @see QueryBuilder::generateQuery()
	 */
	public function generateQuery(){
		$this->checkConditions();
		$this->mergeConditions($this->conditions);
		$distinct = $this->generateDistinct();
		$fields = $this->generateFields();
		$from = $this->generateFrom();
		$where = $this->generateWhere();
		$group = $this->generateGroup();
		$order = $this->generateOrder();
		$limit = $this->generateLimit();
		$query =
<<<SQL
	SELECT {$distinct}
		{$fields}
	FROM
		{$from}
	WHERE
		{$where}
	{$group}
	{$order}
	{$limit}
SQL;
		return $query;
	}

	/**
	 * (non-PHPdoc)
	 * @see QueryBuilder::getQueryString()
	 */
	public function getQueryString(){
		return new \York\Database\QueryBuilder\QueryString($this->generateQuery());
	}
}
