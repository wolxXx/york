<?php
namespace York\Backend\Script;

/**
 * class for running migrations
 *
 * @package \York\Backend
 * @version $version$
 * @author  wolxXx
 */
class Migrator extends \York\Console\Application
{
    const SCRIPT_NAME = 'create and generate migrations';
    
    const SCRIPT_VERSION = '$version$';
    
    /**
     * list of all finished migrations
     *
     * @var array
     */
    protected $finishedMigrations;

    /**
     * list of all new migrations
     *
     * @var array
     */
    protected $newMigrations;

    /**
     * list of all migrations
     *
     * @var array
     */
    protected $migrations;

    /**
     * instance for database connection
     *
     * @var \York\Database\Model
     */
    protected $model;

    /**
     * @inheritdoc
     */
    public function help()
    {
        $this
            ->output('migrations tool. lists (new and finished) migrations.')
            ->newLine()
            ->output('set some of these flags to execute tasks:')
            ->output('-e | --execute: run all new migrations')
            ->output('-i | --number=$migrationNumber: execute only this migration')
            ->output('-f | --finished: list all finished migrations')
            ->output('-l | --list: list all migrations')
            ->output('-n | --new: lists all new migrations')
            ->output('-s | --simulate: simulate the execution of new migrations')
            ->output('-c | --create: create a new migration')
            ->output('-g | --generate: alias for --create')
            ->newLine()
            ->output('please note: only one action once is allowed and will be executed!!!');
    }

    public function beforeRun()
    {
        $this->model = new \York\Database\Model();
        $this
            ->debugOutput('getting migrations from filesystem')
            ->getMigrations()
            ->debugOutput(sprintf('found %s migrations', sizeof($this->migrations)))
            ->debugOutput('getting finished migrations')
            ->getFinishedMigrations()
            ->debugOutput(sprintf('found %s finished migrations', sizeof($this->finishedMigrations)))
            ->debugOutput('getting new migrations')
            ->getNewMigrations()
            ->debugOutput(sprintf('found %s new migrations', sizeof($this->newMigrations)));
    }

    /**
     * @inheritdoc
     */
    public function run()
    {
        $this
            ->output('running migrations tool')
            ->newLine()
            ->grabAction();
    }

    /**
     * grab the specified action and run the resulting function
     * display help if no valid action was set
     */
    protected function grabAction()
    {
        $this->debugOutput('...grabbing flags');

        if (true === \York\Console\Flag::Factory('finished', 'f')->isEnabled()) {
            $this->listFinished();

            return;
        }

        if (true === \York\Console\Flag::Factory('list', 'l')->isEnabled()) {
            $this->listAll();

            return;
        }

        if (true === \York\Console\Flag::Factory('new', 'n')->isEnabled()) {
            $this->listNew();

            return;
        }


        if (true === \York\Console\Flag::Factory('simulate', 's')->isEnabled()) {
            $this->simulate();

            return;
        }

        if (true === \York\Console\Flag::Factory('execute', 'e')->isEnabled()) {
            $this->execute();

            return;
        }

        if (true === \York\Console\Flag::Factory('create', 'c')->isEnabled() || true === \York\Console\Flag::Factory('generate', 'g')->isEnabled()) {
            $this->createMigration();

            return;
        }

        $this
            ->debugOutput('no valid flag set....')
            ->errorOutput('no (valid) action specified. here\'s the help...')
            ->newLine()
            ->help();
        $this->defaultHelp();
    }

    /**
     * create a new migration
     */
    protected function createMigration()
    {
        $migrationNumber = $this->getNewMigrationNumber();
        $this->output('new migration number is ' . $this->getNewMigrationNumber());

        $this->verboseOutput('parsing template');
        $template = \York\Template\Parser::parseFile(__DIR__ . '/../templates/newMigration.php', array(
            'number' => $migrationNumber,
            'generationDate' => \York\Helper\Date::getDateTime()->format('Y-m-d H:i:s')
        ));
        $target = $this->getPathToMigrations() . $migrationNumber . '.php';
        $this->output('writing filecontent to ' . $target);

        file_put_contents($target, $template);

        $this
            ->verboseOutput('migration ' . $migrationNumber . ' created')
            ->newLine()
            ->warningOutput('do not forget to update your models!')
        ;
    }

    /**
     * list all migrations
     */
    protected function listAll()
    {
        foreach ($this->migrations as $current) {
            $suffix = ' (new)';

            foreach ($this->finishedMigrations as $finished) {
                if ($current == $finished->number) {
                    $suffix = ' executed on ' . $finished->created;

                    break;
                }
            }

            $this
                ->output($current . $suffix)
                ->outputMigrationContent($current);
        }
    }

    /**
     * list all new migrations
     */
    protected function listNew()
    {
        $this->verboseOutput('listing new migrations');

        if (true === empty($this->newMigrations)) {
            $this->output('no new migrations found.');
        }

        foreach ($this->newMigrations as $current) {
            $this
                ->output('new migration: ' . $current)
                ->outputMigrationContent($current);

        }
    }

    /**
     * simulate the execution of the migrations
     */
    protected function simulate()
    {
        $this->verboseOutput('simulating execution of new migrations');

        if (0 === sizeof($this->newMigrations)) {
            $this->output('no migrations found for execution');
        }

        foreach ($this->newMigrations as $current) {
            $this
                ->output('executing migration ' . $current)
                ->outputMigrationContent($current, false);
        }
    }

    /**
     * execute the new migrations
     */
    protected function execute()
    {
        $this->verboseOutput('executing new migrations');


        $selection = \York\Console\Parameter::Factory('number', 'i', false)->getValue();
        if (null !== $selection) {

            if (false === isset($this->newMigrations[$selection])) {
                $this->errorOutput(sprintf('given migration %s is not a new migration or is not found!', $selection));
                $this->errorOutput(sprintf('use -l or --list for list all migrations!', $selection));

                return;
            }

            $this->executeMigration($selection);

            return;
        }

        if (true === empty($this->newMigrations)) {
            $this->output('no new migrations found! nothing to do! what a wonderful day!');
        }

        foreach ($this->newMigrations as $current) {
            $this->executeMigration($current);
        }
    }

    /**
     * @param $number
     */
    protected function executeMigration($number)
    {
        $this->output('executing migration ' . $number);
        require_once $this->getPathToMigration($number);
        /**
         * @var \York\Database\Migration $class
         * @var \York\Database\Migration $instance
         */
        $class = sprintf('\Application\Migration\Migration%s', $number);
        $instance = new $class($number);
        $this->verboseOutput('calling run');
        $start = microtime(true);
        $instance->run();
        $end = microtime(true);
        $execution = $end - $start;
        $execution = sprintf("%000002.6f", $execution);
        $execution = str_pad($execution, 9, 0, STR_PAD_LEFT);
        $this->verboseOutput('execution time:' . $execution);
        $this->verboseOutput('calling after run');
        $instance->afterRun();
        $this->output('finished migration ' . $number);
    }

    /**
     * list all finished migrations
     */
    protected function listFinished()
    {
        $this->verboseOutput('listing finished migrations');

        foreach ($this->finishedMigrations as $current) {
            $this->output(sprintf('%s: executed migration #%s', $current->created, $current->number));
        }
    }

    /**
     * get all migrations found in file system
     *
     * @return $this
     */
    protected function getMigrations()
    {
        $this->migrations = array();
        $files = scandir($this->getPathToMigrations());

        foreach ($files as $current) {
            if (false === strstr($current, '.php')) {
                continue;
            }

            $name = str_replace('.php', '', $current);
            $this->migrations[$name] = $name;
        }

        if (false === empty($this->migrations)) {
            asort($this->migrations);
        }

        return $this;
    }

    /**
     * get all migrations that are not found in the database
     *
     * @return $this
     */
    protected function getNewMigrations()
    {
        $this->newMigrations = array();
        foreach ($this->migrations as $current) {
            $isFinished = false;

            foreach ($this->finishedMigrations as $finished) {
                if ($current == $finished->number) {
                    $isFinished = true;

                    break;
                }
            }

            if (false === $isFinished) {
                $this->newMigrations[$current] = $current;
            }
        }

        if (false === empty($this->newMigrations)) {
            asort($this->newMigrations);
        }

        return $this;

    }

    /**
     * get all migrations found in the database
     *
     * @return $this
     */
    protected function getFinishedMigrations()
    {
        $this->finishedMigrations = $this->model->find(array(
            'from' => 'migrations'
        ));

        return $this;
    }

    /**
     * get the next number for a migration
     *
     * @return string
     */
    protected function getNewMigrationNumber()
    {
        return str_pad(end($this->migrations) + 1, 3, '0', STR_PAD_LEFT);
    }

    /**
     * echoes the content of a migration
     *
     * @param integer $number
     * @param boolean $verbose
     * @return $this
     */
    protected function outputMigrationContent($number, $verbose = true)
    {
        $function = 'output';

        if (true === $verbose) {
            $function = 'verboseOutput';

        }

        call_user_func_array(array($this, $function), array(
            'file content: ',
            \York\Console\Style::styleString($this->getMigrationContent($number), \York\Console\Style::FOREGROUND_YELLOW),
            '',
            '',
            '_____________',
            '',
            ''
        ));

        return $this;
    }

    /**
     * retrieves the content from the migration
     *
     * @param integer $number
     * @return string
     */
    protected function getMigrationContent($number)
    {
        return file_get_contents($this->getPathToMigrations() . $number . '.php');
    }

    /**
     * retrieves the path to the migrations
     *
     * @throws \York\Exception\General
     * @return string
     */
    protected function getPathToMigrations()
    {
        try {
            $path = \York\Helper\Application::getApplicationRoot() . 'migrations/';
            new \York\FileSystem\Directory($path);

            return $path;
        } catch (\Exception $exception) {
        }

        try {
            $path = \York\Helper\Application::getApplicationRoot() . 'Migration/';
            new \York\FileSystem\Directory($path);

            return $path;
        } catch (\Exception $exception) {
        }

        throw new \York\Exception\General('could not find path to migrations.');
    }

    /**
     * retrieves the path to the given migration
     *
     * @param integer $number
     * @return string
     */
    protected function getPathToMigration($number)
    {
        return $this->getPathToMigrations() . $number . '.php';
    }
}

