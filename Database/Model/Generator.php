<?php
namespace York\Database\Model;
use York\Database\Information;
use York\Dependency\Manager;
use York\Helper\String;
use York\HTML\Element\Password;
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
	 * setup
	 */
	public function __construct(){
		$this->databaseManager = Manager::get('databaseManager');
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
	 * generates a model for the given table
	 *
	 * @param string $table
	 */
	public function generate($table){
		echo 'genrating blueprint for model '.$table.PHP_EOL;

		$schema = $this->databaseManager->getConnection()->getSchema();
		$columns = Information::getColumnsForTable($schema, $table);
		$fileText = '';
		$flatMembers = array();
		$referencedMembers = array();
		$classMemberVisibility = 'protected';

		$memberText = Parser::parseFile(__DIR__.'/Generator/member', array());
		$referencedMemberText = Parser::parseFile(__DIR__.'/Generator/referencedMember', array());
		$getterSetterText = Parser::parseFile(__DIR__.'/Generator/getterSetter', array());


		foreach($columns as $current){
			$name = $current->COLUMN_NAME;
			$type = Information::getTypeOfColumn($schema, $table, $name)->DATA_TYPE;
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
				'model' => ucfirst(String::underscoresToPascalCase($table))
			));


			$flatMembers[] = $name;

			if('id_' !== substr($name, 0, 3)){
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

		$params = array(
			'tablename' => $table,
			'modelname' => String::underscoresToPascalCase($table),
			'classmembers' => $fileText
		);

		$targetDir = \York\Helper\Application::getApplicationRoot().'Model/Blueprint/';

		$target = $targetDir.$params['modelname'].'.php';

		$parsed = Parser::parseFile(__DIR__.'/Generator/skeleton', $params);

		echo 'writing file '.$target.PHP_EOL;
		file_put_contents($target, $parsed);

		echo 'done.'.PHP_EOL.PHP_EOL;
	}

	/**
	 * generates all models from the current database
	 */
	public function generateAll(){
		foreach(Information::getAllTables($this->databaseManager->getConnection()->getSchema()) as $table){
			usleep(400000);
			$this->generate($table->TABLE_NAME);
		}
	}
}
