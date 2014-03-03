<?php
namespace York\Database\Accessor;
/**
 * factory class for database objects
 * currently supported: save, update, delete, multiDelete
 *
 * @author wolxXx
 * @version 3.0
 * @package York\Database\Accessor
 */

class Factory {
	/**
	 * creates a new save object
	 *
	 * @param string $tableName
	 * @return \York\Database\Accessor\Save
	 */
	public static function getSaveObject($tableName){
		return new \York\Database\Accessor\Save($tableName);
	}

	/**
	 * creates a new update object
	 *
	 * @param string $table
	 * @param integer $rowId
	 * @return \York\Database\Accessor\Update
	 */
	public static function getUpdateObject($table = null, $rowId= null){
		return new \York\Database\Accessor\Update($table, $rowId);
	}

	/**
	 * creates a new delete object
	 *
	 * @param string $table
	 * @param integer $rowId
	 * @return \York\Database\Accessor\Delete
	 */
	public static function getDeleteObject($table = null, $rowId= null){
		return new \York\Database\Accessor\Delete($table, $rowId);
	}

	/**
	 * creates a new multi delete object
	 *
	 * @param string $table
	 * @param array $conditions
	 * @return \York\Database\Accessor\MultiDelete
	 */
	public static function getMultiDeleteObject($table = null, $conditions = array()){
		return new \York\Database\Accessor\MultiDelete($table, $conditions);
	}

	/**
	 * creates a new multi update object
	 *
	 * @param string $table
	 * @param array $data
	 * @param array $conditions
	 * @return \York\Database\Accessor\MultiUpdate
	 */
	public static function getMultiUpdateObject($table = null, $data = null, $conditions = null){
		return new \York\Database\Accessor\MultiUpdate($table, $data, $conditions);
	}
} 