<?php
use York\Console\Application;
use York\Console\Parameter;
use York\Database\Model\Generator;

require_once(__DIR__.'/Bootstrap.php');

/**
 * generates the blueprints
 * creates manager and model if not existent yet
 *
 * @author wolxXx
 * @version 3.0
 */
class generateModels extends Application{
	/**
	 * flag if force is enabled
	 * used for overwriting files
	 *
	 * @var boolean
	 */
	protected $isForceEnabled;

	/**
	 * flag for do not generate blueprint
	 *
	 * @var boolean
	 */
	protected $isExcludeBlueprintEnabled;

	/**
	 * flag for do not generate manager
	 *
	 * @var boolean
	 */
	protected $isExcludeManagerEnabled;

	/**
	 * flag for do not generate model
	 *
	 * @var boolean
	 */
	protected $isExcludeModelEnabled;

	/**
	 * @inheritdoc
	 */
	public function help(){
		$this->output('generate models from database');
		$this->output('');
		$this->output('options:');
		$this->output('-m | --model=$name: only for this model. csv allowed. optional. default = all');
		$this->output('-l | --list: list all tables ');
		$this->output('--force: !overwrite! !all! files!! handle with care!!!');
		$this->output('--exclude-blueprint: do not generate blueprints');
		$this->output('--exclude-manager: do not generate manager');
		$this->output('--exclude-model: do not generate models');
	}

	/**
	 * grab the flags
	 */
	protected function grabFlags(){
		$this->isForceEnabled = \York\Console\Flag::Factory('force')->isEnabled();
		$this->isExcludeBlueprintEnabled = \York\Console\Flag::Factory('exclude-blueprint')->isEnabled();
		$this->isExcludeManagerEnabled = \York\Console\Flag::Factory('exclude-manager')->isEnabled();
		$this->isExcludeModelEnabled = \York\Console\Flag::Factory('exclude-model')->isEnabled();
	}

	/**
	 * @inheritdoc
	 */
	public function run(){
		$logger = \York\Dependency\Manager::get('logger');
		$logger->addLogger(new \York\Logger\File('generateModels.all', $logger::LEVEL_ALL));
		$logger->addLogger(new \York\Logger\File('generateModels.db.err', $logger::LEVEL_DATABASE_ERROR));
		$logger->addLogger(new \York\Logger\File('generateModels.debug', $logger::LEVEL_DEBUG));
		$logger->addLogger(new \York\Logger\File('generateModels.db.debug', $logger::LEVEL_DATABASE_DEBUG));

		$this->grabFlags();

		if(true === \York\Console\Flag::Factory('list', 'l')->isEnabled()){
			$this->listTables();
			return;
		}
		$model = Parameter::Factory('model', 'm', false, 'all')->getValue();
		if('all' === $model){
			$this->verboseOutput('no model criteria found. generating for all tables');
			$this->generateAll();
			return;
		}

		foreach(explode(',', $model) as $model){
			$this->verboseOutput('model criteria found: generating table '.$model);
			$this->generateModel($model);
		}
	}

	/**
	 * get all tables from the configured database
	 *
	 * @return string[]
	 */
	protected function getTables(){
		$tables = array();
		foreach(\York\Database\Information ::getAllTables(York\Dependency\Manager::get('databaseManager')->getConnection()->getSchema()) as $table){
			$tables[] = $table->TABLE_NAME;
		}
		asort($tables);

		return $tables;

	}

	/**
	 * lists all tables
	 */
	protected function listTables(){
		$this->output('listing tables');
		$generator = new Generator('');
		foreach($this->getTables() as $table){
			$model = \York\Helper\String::underscoresToPascalCase($table);
			$out = sprintf(' - %s', $table);
			$this->output($out);

			$green = \York\Console\Style::FOREGROUND_GREEN;
			$red = \York\Console\Style::FOREGROUND_RED;
			$existent = $this->colorString('exists', $green);
			$notExistent = $this->colorString('not existent', $red);

			$appendix = $existent;
			if(false === file_exists($generator->getPathForManager($table))){
				$appendix = $notExistent;
			}
			$this->output('    - manager: '.$appendix);


			$appendix = $existent;
			if(false === file_exists($generator->getPathForModel($table))){
				$appendix = $notExistent;
			}
			$this->output('    - model: '.$appendix);


			$appendix = $existent;
			if(false === file_exists($generator->getPathForBlueprint($table))){
				$appendix = $notExistent;
			}
			$this->output('    - blueprint: '.$appendix);
		}
	}

	/**
	 * generates the blueprint, manager, model for all found tables
	 */
	protected function generateAll(){
		$this->verboseOutput('clearing already found blueprints');
		$path = sprintf('%sModel/Blueprint/*', \York\Helper\Application::getApplicationRoot());
		York\Console\SystemCall::Factory('rm', array($path))->run();
		foreach($this->getTables() as $table){
			$this->generateModel($table);
		}
	}

	/**
	 * generates the blueprint, manager, model for the specified table
	 *
	 * @param string $model
	 * @return $this
	 */
	protected function generateModel($table){
		$schema = \York\Dependency\Manager::get('databaseManager')->getConnection()->getSchema();

		if(false === \York\Database\Information::tableExists($schema, $table)){
			$this->errorOutput('table not found: '.$table);
			return $this;
		}

		$generator = new Generator($table);

		$this->newLine();
		$this->output('current table: '.$this->colorString($table, \York\Console\Style::FOREGROUND_YELLOW));

		$this->outputPrefix .= '   ';
		$this
			->generatorGenerateBlueprint($generator, $table)
			->generatorGenerateModel($generator, $table)
			->generatorGenerateManager($generator, $table);
		$this->outputPrefix = $this->defaultOutputPrefix;

		return $this;
	}

	/**
	 * generate the model
	 *
	 * @param Generator $generator
	 * @param string $table
	 * @return $this
	 */
	protected function generatorGenerateModel($generator, $table){
		if(true === $this->isExcludeModelEnabled){
			$this->output('excluding model');

			return $this;
		}

		$this->output('generating model');
		$generator->generateModel($table, $this->isForceEnabled);
		$this->verboseOutput('  can be found under'. $generator->getPathForModel($table));

		return $this;
	}

	/**
	 * generate manager
	 *
	 * @param Generator $generator
	 * @param string $table
	 * @return $this
	 */
	protected function generatorGenerateManager($generator, $table){
		if(true === $this->isExcludeManagerEnabled){
			$this->output('excluding manager');

			return $this;
		}

		$this->output('generating manager');
		$generator->generateManager($table, $this->isForceEnabled);
		$this->verboseOutput('  can be found under'. $generator->getPathForManager($table));

		return $this;
	}

	/**
	 * generate the blueprint
	 *
	 * @param Generator $generator
	 * @param string $table
	 * @return $this
	 */
	protected function generatorGenerateBlueprint($generator, $table){
		if(true === $this->isExcludeBlueprintEnabled){
			$this->output('excluding blueprint');

			return $this;
		}
		$this->output('generating blueprint');
		$generator->generateBlueprint($table, true);
		$this->verboseOutput('  can be found under'. $generator->getPathForBlueprint($table));

		return $this;
	}
}

/**
 * execute
 */
new generateModels('generate models', '1.2');


