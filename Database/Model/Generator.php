<?php
namespace York\Database\Model;
use York\Database\Information;
use York\Dependency\Manager as Dependency;
use York\Helper\String;
use York\Template\Parser;

/**
 * generator for database blueprints
 *
 * @author wolxXx
 * @version 3.0
 * @package York\Database\Model
 */
class Generator {
	/**
	 * @var \York\Database\Manager $databaseManager
	 */
	protected $databaseManager;

	/**
	 * table name
	 *
	 * @var string
	 */
	protected $table;

	/**
	 * setup
	 */
	public function __construct($table){
		$this->table = $table;
		$this->databaseManager = Dependency::get('databaseManager');
	}

	/**
	 * match the database field configuration to a php equivalent
	 *
	 * @param $type
	 * @return string
	 */
	public function matchDatabaseTypeToPHP($type){
		if('datetime' === $type){
			return '\DateTime';
		}

		if('tinyint' === $type){
			return 'boolean';
		}

		if('int' === $type){
			return 'integer';
		}

		if(true === in_array($type, array('varchar', 'text'))){
			return 'string';
		}

		return 'string';
	}

	/**
	 * generates blueprint, manager and model with default settings
	 *
	 * @param string $table
	 * @return $this
	 *
	 */
	public function generate(){
		return $this
			->generateBlueprint()
			->generateModel()
			->generateManager();
	}

	/**
	 * generates the blueprint
	 * overwrites the eventually found file if wanted
	 *
	 * @param boolean $overwrite
	 * @return $this
	 * todo refactor!
	 */
	public function generateBlueprint($overwrite = true){
		$target = $this->getPathForBlueprint($this->table);

		if(true === file_exists($target)){
			if(false === $overwrite){
				return $this;
			}
			unlink($target);
		}

		$schema = $this->databaseManager->getConnection()->getSchema();
		$columns = Information::getColumnsForTable($schema, $this->table);
		$fileText = '';
		$flatMembers = array();
		$referencedMembers = array();
		$classMemberVisibility = 'protected';
		$memberText = Parser::parseFile(__DIR__.'/Generator/member', array());
		$referencedMemberText = Parser::parseFile(__DIR__.'/Generator/referencedMember', array());
		$getterSetterText = Parser::parseFile(__DIR__.'/Generator/getterSetter', array());
		$fileTextTemplate = Parser::parseFile(__DIR__.'/Generator/skeleton_blueprint', array());

		foreach($columns as $current){
			$name = $current->COLUMN_NAME;
			$type = Information::getTypeOfColumn($schema, $this->table, $name)->DATA_TYPE;
			$type = $this->matchDatabaseTypeToPHP($type);

			$fileText .= Parser::parseText($memberText, array(
				'visibility' => $classMemberVisibility,
				'type' => $type,
				'name' => $name
			));

			$fileText .= Parser::parseText($getterSetterText, array(
				'name' => $name,
				'uname' => ucfirst($name),
				'type' => $type,
				'model' => ucfirst(String::underscoresToPascalCase($this->table))
			));


			$flatMembers[] = $name;

			if(false === String::startsWith($name, 'id_')){
				continue;
			}

			$class = String::underscoresToPascalCase(substr($name, 3));
			$model = '\Application\Model\\'.$class;

			$fileText .= Parser::parseText($referencedMemberText, array(
				'class' => $model,
				'name' => $class,
				'identified' => $name
			));

			$referencedMembers[] = $class;
		}

		$flatMembers = \York\Helper\Set::decorate($flatMembers, "'", "'");
		$fileText .= sprintf('/**
	* @var array $flatMembers
	*/', implode(', ', $flatMembers)).PHP_EOL."\t";
		$fileText .= sprintf('public $flatMembers = array(%s);', implode(', ', $flatMembers)).PHP_EOL.PHP_EOL;

		$referencedMembers = \York\Helper\Set::decorate($referencedMembers, "'", "'");
		$fileText .= sprintf('/**
	* @var array $referencedMembers
	*/', implode(', ', $referencedMembers)).PHP_EOL."\t";
		$fileText .= sprintf('public $referencedMembers = array(%s);', implode(', ', $referencedMembers)).PHP_EOL.PHP_EOL;

		file_put_contents($target, Parser::parseText($fileTextTemplate, array(
			'tablename' => $this->table,
			'modelname' => String::underscoresToPascalCase($this->table),
			'classmembers' => $fileText
		)));

		return $this;
	}

	/**
	 * generates the model class file
	 * overwrites the eventually found file if wanted
	 *
	 * @param boolean $overwrite
	 * @return $this
	 */
	public function generateModel($overwrite = false){
		$model = String::underscoresToPascalCase($this->table);
		$target = \York\Helper\Application::getApplicationRoot().'Model/'.$model.'.php';
		if(true === file_exists($target)){
			if(false === $overwrite){
				return $this;
			}
			unlink($target);
		}

		file_put_contents($target, Parser::parseFile(__DIR__.'/Generator/skeleton_model', array(
			'modelname' => $model
		)));

		return $this;
	}

	/**
	 * generates the manager class file
	 * overwrites the eventually found file if wanted
	 *
	 * @param boolean $overwrite
	 * @return $this
	 */
	public function generateManager($overwrite = false){
		$model = String::underscoresToPascalCase($this->table);
		$target = \York\Helper\Application::getApplicationRoot().'Model/Manager/'.$model.'.php';
		if(true === file_exists($target)){
			if(false === $overwrite){
				return $this;
			}
			unlink($target);
		}

		file_put_contents($target, Parser::parseFile(__DIR__.'/Generator/skeleton_manager', array(
			'modelname' => $model,
			'table' => $this->table
		)));

		return $this;
	}

	protected function getPath($class, $infix = null){
		if(null === $infix){
			$infix = '';
		}else{
			$infix .= '/';
		}

		return \York\Helper\Application::getApplicationRoot().'Model/'.$infix.$class.'.php';
	}

	/**
	 * get the path for the model for the given table
	 *
	 * @param $table
	 * @return string
	 */
	public function getPathForModel($table){
		return $this->getPath(String::underscoresToPascalCase($table));
	}

	/**
	 * get the path for the manager for the given table
	 *
	 * @param $table
	 * @return string
	 */
	public function getPathForManager($table){
		return $this->getPath(String::underscoresToPascalCase($table), 'Manager');
	}

	/**
	 * get the path for the blueprint for the given table
	 *
	 * @param $table
	 * @return string
	 */
	public function getPathForBlueprint($table){
		return $this->getPath(String::underscoresToPascalCase($table), 'Blueprint');
	}

	/**
	 * generates models for all found tables in the current database
	 *
	 * @return $this
	 */
	public function generateAll(){
		$tableSave = $this->table;

		foreach(Information::getAllTables($this->databaseManager->getConnection()->getSchema()) as $table){
			$this->table = $table->TABLE_NAME;
			$this->generate();
		}

		$this->table = $tableSave;

		return $this;
	}
}
