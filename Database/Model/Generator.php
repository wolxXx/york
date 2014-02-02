<?php
namespace York\Database\Model;


use York\HTML\Element\Password;

class Generator {
	/**
	 * @var \York\Database\Manager $databaseManager
	 */
	protected $databaseManager;
	public function __construct(){
		$this->databaseManager = \York\Dependency\Manager::get('databaseManager');
	}

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
		$columns = \York\Database\Information::getColumnsForTable($schema, $table);
		$members = '';
		$flatMembers = array();
		$referencedMembers = array();
		foreach($columns as $current){
			$name = $current->COLUMN_NAME;
			$type = \York\Database\Information::getTypeOfColumn($schema, $table, $name)->DATA_TYPE;
			$type = $this->matchDatabaseTypeToPHP($type);

			$members .= sprintf('/**
	* @var %s $%s
	*/
	'
				, $type, $name);
			$members .= sprintf('public $%s;%s%s', $name, PHP_EOL.PHP_EOL, "\t");
			$flatMembers[] = $name;

			if('id_' !== substr($name, 0, 3)){
				continue;
			}
			$class = \York\Helper\String::underscoresToPascalCase(substr($name, 3));
			$model = '\Application\Model\Blueprint\\'.$class;
			$members .= sprintf('/**
	* @var %s $%s
	* @identifiedBy %s
	*/
	'
				, $model, $class, $name);
			$members .= sprintf('public $%s;%s%s', $class, PHP_EOL.PHP_EOL, "\t");

			$referencedMembers[] = $class;
		}

		$flatMembers = \York\Helper\Set::decorate($flatMembers, "'", "'");
		$members .= sprintf('/**
	* @var array $flatMembers
	*/', implode(', ', $flatMembers)).PHP_EOL."\t";
		$members .= sprintf('protected $flatMembers = array(%s);', implode(', ', $flatMembers)).PHP_EOL.PHP_EOL;

		$referencedMembers = \York\Helper\Set::decorate($referencedMembers, "'", "'");
		$members .= sprintf('/**
	* @var array $referencedMembers
	*/', implode(', ', $referencedMembers)).PHP_EOL."\t";
		$members .= sprintf('protected $referencedMembers = array(%s);', implode(', ', $referencedMembers)).PHP_EOL.PHP_EOL;

		$params = array(
			'tablename' => $table,
			'modelname' => \York\Helper\String::underscoresToPascalCase($table),
			'classmembers' => $members
		);

		$targetDir = \York\Helper\Application::getApplicationRoot().'Model/Blueprint/';

		$targetDirObject = new \York\FileSystem\Directory($targetDir, true);

		$target = $targetDir.$params['modelname'].'.php';

		$parsed = \York\Template\Parser::parseFile(__DIR__.'/skeleton', $params);

		#\York\Helper\Application::debug($target, $parsed, \York\Helper\FileSystem::getFileName($target));

		#return;

		echo 'writing file '.$target.PHP_EOL;
		file_put_contents($target, $parsed);

		echo 'done.'.PHP_EOL.PHP_EOL;
	}

	/**
	 * generates all models from the current database
	 */
	public function generateAll(){
		foreach(\York\Database\Information::getAllTables($this->databaseManager->getConnection()->getSchema()) as $table){
			usleep(400000);
			$this->generate($table->TABLE_NAME);
		}
	}
}
