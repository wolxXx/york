<?php
namespace York\Backend\Script;

/**
 * generates the blueprints
 * creates manager and model if not existent yet
 *
 * @package \York\Backend
 * @version $version$
 * @author  wolxXx
 */
class ModelGenerator extends \York\Console\Application
{
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
     * @var boolean
     */
    protected $isExcludeManagerBlueprintEnabled;

    /**
     * flag for do not generate model
     *
     * @var boolean
     */
    protected $isExcludeModelEnabled;

    /**
     * @var boolean
     */
    protected $isListEnabled;

    /**
     * @var string
     */
    protected $model;

    /**
     * @var \York\Database\Model\Generator
     */
    protected $generator;

    /**
     * @inheritdoc
     */
    public function help()
    {
        $this
            ->output('generate models from database')
            ->output('')
            ->output('options:')
            ->output('-m | --model=$name: only for this model. csv allowed. optional. default = all')
            ->output('-l | --list: list all tables ')
            ->output('--force: !overwrite! !all! files!! handle with care!!!')
            ->output('--exclude-blueprint: do not generate blueprints')
            ->output('--exclude-manager: do not generate manager')
            ->output('--exclude-manager-blueprint: do not generate manager blueprint')
            ->output('--exclude-model: do not generate models');
    }

    /**
     * grab the flags
     */
    protected function grabFlags()
    {
        $this->isForceEnabled = \York\Console\Flag::Factory('force')->isEnabled();
        $this->isExcludeBlueprintEnabled = \York\Console\Flag::Factory('exclude-blueprint')->isEnabled();
        $this->isExcludeManagerEnabled = \York\Console\Flag::Factory('exclude-manager')->isEnabled();
        $this->isExcludeManagerBlueprintEnabled = \York\Console\Flag::Factory('exclude-manager-blueprint')->isEnabled();
        $this->isExcludeModelEnabled = \York\Console\Flag::Factory('exclude-model')->isEnabled();
        $this->isListEnabled = \York\Console\Flag::Factory('list', 'l')->isEnabled();
        $this->model = \York\Console\Parameter::Factory('model', 'm', false, 'all')->getValue();
    }

    /**
     * @inheritdoc
     */
    public function run()
    {
        $logger = \York\Dependency\Manager::getLogger();

        $logger
            ->addLogger(\York\Logger\StandardOut::Factory()->setLevel(\York\Logger\Level::ALL))
            ->addLogger(\York\Logger\File::Factory()->setFilePath('generateModels.all')->setLevel(\York\Logger\Level::ALL))
            ->addLogger(\York\Logger\File::Factory()->setFilePath('generateModels.db.err')->setLevel(\York\Logger\Level::DATABASE_ERROR))
            ->addLogger(\York\Logger\File::Factory()->setFilePath('generateModels.debug')->setLevel(\York\Logger\Level::DEBUG))
            ->addLogger(\York\Logger\File::Factory()->setFilePath('generateModels.db.debug')->setLevel(\York\Logger\Level::DATABASE_DEBUG))
        ;

        $this->grabFlags();

        if (true === $this->isListEnabled) {
            $this->listTables();

            return;
        }

        if ('all' === $this->model) {
            $this
                ->verboseOutput('no model criteria found. generating for all tables')
                ->generateAll();

            return;
        }

        foreach (explode(',', $this->model) as $model) {
            $this->verboseOutput('model criteria found: generating table ' . $model);
            $this->generateModel($model);
        }
    }

    /**
     * @param \York\Database\Model\Generator $generator
     * @return $this
     */
    protected function setGenerator(\York\Database\Model\Generator $generator)
    {
        $this->generator = $generator;

        return $this;
    }

    /**
     * get all tables from the configured database
     *
     * @return string[]
     */
    protected function getTables()
    {
        $tables = array();
        $schema = \York\Dependency\Manager::getDatabaseManager()->getConnection()->getSchema();
        $tablesInSchema = \York\Database\Information::getAllTables($schema);

        foreach ($tablesInSchema as $table) {
            $tables[] = $table->TABLE_NAME;
        }

        asort($tables);

        return $tables;

    }

    /**
     * lists all tables
     */
    protected function listTables()
    {
        $this->output('listing tables');
        $foundForTable = array();

        $green = \York\Console\Style::FOREGROUND_GREEN;
        $red = \York\Console\Style::FOREGROUND_RED;
        $yellow = \York\Console\Style::FOREGROUND_YELLOW;

        $existent = $this->colorString('exists', $green);
        $notExistent = $this->colorString('not existent', $red);
        $notUpToDate = $this->colorString('not up to date', $yellow);
        $upToDate = $this->colorString('up to date', $green);

        foreach ($this->getTables() as $table) {
            $foundForTable[] = $table;
            $generator = new \York\Database\Model\Generator($table);
            $generator->setPathPrefix();
            $out = sprintf(' - %s', $table);
            $this->output($out);


            $appendix = $upToDate;

            if (false === file_exists($generator->getPathForBlueprint())) {
                $appendix = $notExistent;
            } else {
                $generator->setPathPrefix('/tmp/');
                $generator->generateBlueprint();
                $tempBlueprint = file_get_contents($generator->getPathForBlueprint());
                $generator->setPathPrefix();
                $originalBlueprint = file_get_contents($generator->getPathForBlueprint());

                if ($originalBlueprint !== $tempBlueprint) {
                    $appendix = $notUpToDate;
                }
            }

            $this->output('    - model blueprint: ' . $appendix);

            $appendix = $existent;

            if (false === file_exists($generator->getPathForModel())) {
                $appendix = $notExistent;
            }

            $this->output('    - model: ' . $appendix);

            $appendix = $existent;

            if (false === file_exists($generator->getPathForManager())) {
                $appendix = $notExistent;
            }

            $this->output('    - manager: ' . $appendix);

            $appendix = $existent;

            if (false === file_exists($generator->getPathForManagerBlueprint())) {
                $appendix = $notExistent;
            }

            $this->output('    - manager blueprint: ' . $appendix);
        }

        $modelFiles = \York\Helper\FileSystem::scanDirectory(\York\Helper\Application::getApplicationRoot() . 'Model');

        foreach ($modelFiles as $modelFileIndex => $modelFile) {
            foreach ($foundForTable as $index => $table) {
                $generator = new \York\Database\Model\Generator($table);

                if ($generator->getPathForModel() === $modelFile) {
                    unset($foundForTable[$index]);
                    unset($modelFiles[$modelFileIndex]);
                }
            }
        }

        foreach ($modelFiles as $modelFile) {
            $this->warningOutput(sprintf('model found without table: %s', str_replace(\York\Helper\Application::getProjectRoot(), '', $modelFile)));
        }
    }

    /**
     * generates the blueprint, manager, model for all found tables
     */
    protected function generateAll()
    {
        $this->verboseOutput('clearing already found blueprints and manager blueprints');

        $path = sprintf('%sModel/Blueprint/*', \York\Helper\Application::getApplicationRoot());
        \York\Console\SystemCall::Factory('rm', array($path))->run();

        $path = sprintf('%sModel/Manager/Blueprint/*', \York\Helper\Application::getApplicationRoot());
        \York\Console\SystemCall::Factory('rm', array($path))->run();

        foreach ($this->getTables() as $table) {
            $this->generateModel($table);
        }
    }

    /**
     * generates the blueprint, manager, model for the specified table
     *
     * @param string $table
     * @return $this
     */
    protected function generateModel($table)
    {
        $schema = \York\Dependency\Manager::getDatabaseManager()->getConnection()->getSchema();

        if (false === \York\Database\Information::tableExists($schema, $table)) {
            $this->errorOutput('table not found: ' . $table);

            return $this;
        }

        $this->setGenerator(new \York\Database\Model\Generator($table));

        $this->newLine();
        $this->output('current table: ' . $this->colorString($table, \York\Console\Style::FOREGROUND_YELLOW));

        $this->outputPrefix .= '   ';

        $this
            ->generatorGenerateBlueprint()
            ->generatorGenerateModel()
            ->generatorGenerateManagerBlueprint()
            ->generatorGenerateManager();
        $this->outputPrefix = $this->defaultOutputPrefix;

        return $this;
    }

    /**
     * generate the model
     *
     * @return $this
     */
    protected function generatorGenerateModel()
    {
        if (true === $this->isExcludeModelEnabled) {
            $this->output('excluding model');

            return $this;
        }

        $this->output('generating model');
        $this->generator->generateModel($this->isForceEnabled);
        $this->verboseOutput('  can be found under' . $this->generator->getPathForModel());

        return $this;
    }

    /**
     * generate manager
     *
     * @return $this
     */
    protected function generatorGenerateManager()
    {
        if (true === $this->isExcludeManagerEnabled) {
            $this->output('excluding manager');

            return $this;
        }

        $this->output('generating manager');
        $this->generator->generateManager($this->isForceEnabled);
        $this->verboseOutput('  can be found under' . $this->generator->getPathForManager());

        return $this;
    }

    /**
     * generate manager blueprint
     *
     * @return $this
     */
    protected function generatorGenerateManagerBlueprint()
    {
        if (true === $this->isExcludeManagerBlueprintEnabled) {
            $this->output('excluding manager blueprint');

            return $this;
        }

        $this->output('generating manager blueprint');
        $this->generator->generateManagerBlueprint();
        $this->verboseOutput('  can be found under' . $this->generator->getPathForManagerBlueprint());

        return $this;
    }

    /**
     * generate the blueprint
     *
     * @return $this
     */
    protected function generatorGenerateBlueprint()
    {
        if (true === $this->isExcludeBlueprintEnabled) {
            $this->output('excluding model blueprint');

            return $this;
        }

        $this->output('generating model blueprint');
        $this->generator->generateBlueprint();
        $this->verboseOutput('  can be found under' . $this->generator->getPathForBlueprint());

        return $this;
    }
}
