<?php
namespace York\Database\Model;

use York\Database\QueryBuilder;

interface ManagerInterface {
	public function getById($id);

	public function find(QueryBuilder $query);

}
