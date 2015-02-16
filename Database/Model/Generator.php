<?php
namespace York\Database\Model;

/**
 * generator for database blueprints
 *
 * @package York\Database\Model
 * @version $version$
 * @author wolxXx
 */
class Generator
{
    /**
     * table name
     *
     * @var string
     */
    protected $table;

    /**
     * @var string
     */
    protected $model;

    /**
     * @var string
     */
    protected $pathPrefix;

    /**
     * setup
     */
    public function __construct($table)
    {
        $this->table = $table;
        $this->model = ucfirst(\York\Helper\String::underscoresToPascalCase($this->table));
        $this->setPathPrefix();
    }

    /**
     * match the database field configuration to a php equivalent
     *
     * @param string $type
     *
     * @return string
     */
    public function matchDatabaseTypeToPHP($type)
    {
        if ('datetime' === $type) {
            return '\DateTime';
        }

        if ('tinyint' === $type) {
            return 'boolean';
        }

        if ('int' === $type) {
            return 'integer';
        }

        if (true === in_array($type, array('varchar', 'text'))) {
            return 'string';
        }

        return 'string';
    }

    /**
     * generates blueprint, manager and model with default settings
     *
     * @param string $table
     *
     * @return $this
     *
     */
    public function generate()
    {
        return $this
            ->generateBlueprint()
            ->generateModel()
            ->generateManagerBlueprint()
            ->generateManager();
    }

    /**
     * generates the blueprint
     * overwrites the eventually found file if wanted
     *
     * @param boolean   $overwrite
     *
     * @return $this
     * todo refactor! ouhya. really!!!!
     */
    public function generateBlueprint($overwrite = true)
    {
        $target = $this->getPathForBlueprint();
        $targetPath = $this->getPathForBlueprint(false);

        new \York\FileSystem\Directory($targetPath, true);

        if (true === file_exists($target)) {
            if (false === $overwrite) {
                return $this;
            }

            unlink($target);
        }

        $schema = \York\Dependency\Manager::getDatabaseManager()->getConnection()->getSchema();
        $columns = \York\Database\Information::getColumnsForTable($schema, $this->table);
        $fileText = '';
        $flatMembers = array();
        $referencedMembers = array();
        $classMemberVisibility = 'protected';
        $memberText = \York\Template\Parser::parseFile(__DIR__ . '/Generator/member', array());
        $referencedMemberText = \York\Template\Parser::parseFile(__DIR__ . '/Generator/referencedMember', array());
        $getterSetterText = \York\Template\Parser::parseFile(__DIR__ . '/Generator/getterSetter', array());
        $fileTextTemplate = \York\Template\Parser::parseFile(__DIR__ . '/Generator/skeleton_blueprint', array());
        $getManagerText = \York\Template\Parser::parseFile(__DIR__ . '/Generator/getManager', array());
        $factoryText = \York\Template\Parser::parseFile(__DIR__ . '/Generator/factory', array());

        foreach ($columns as $current) {
            $name = $current->COLUMN_NAME;
            $type = \York\Database\Information::getTypeOfColumn($schema, $this->table, $name)->DATA_TYPE;
            $type = $this->matchDatabaseTypeToPHP($type);

            $fileText .= \York\Template\Parser::parseText($memberText, array(
                'visibility' => $classMemberVisibility,
                'type' => $type,
                'name' => $name
            ));

            $fileText .= \York\Template\Parser::parseText($getterSetterText, array(
                'name' => $name,
                'uname' => ucfirst($name),
                'type' => $type,
                'model' => $this->model
            ));


            $flatMembers[] = $name;

            if (false === \York\Helper\String::startsWith($name, 'id_')) {
                continue;
            }

            $class = \York\Helper\String::underscoresToPascalCase(substr($name, 3));
            $model = '\Application\Model\\' . $class;

            $fileText .= \York\Template\Parser::parseText($referencedMemberText, array(
                'class' => $model,
                'name' => $class,
                'identified' => $name
            ));

            $referencedMembers[] = $class;
        }

        $flatMembers = \York\Helper\Set::decorate($flatMembers, "'", "'");
        $fileText .= sprintf('/**
	* @var array $flatMembers
	*/', implode(', ', $flatMembers)) . PHP_EOL . "\t";
        $fileText .= sprintf('public $flatMembers = array(%s);', implode(', ', $flatMembers)) . PHP_EOL . PHP_EOL;

        $referencedMembers = \York\Helper\Set::decorate($referencedMembers, "'", "'");
        $fileText .= sprintf('/**
	* @var array $referencedMembers
	*/', implode(', ', $referencedMembers)) . PHP_EOL . "\t";
        $fileText .= sprintf('public $referencedMembers = array(%s);', implode(', ', $referencedMembers)) . PHP_EOL . PHP_EOL;

        $fileText .= \York\Template\Parser::parseText($getManagerText, array(
            'model' => \York\Helper\String::underscoresToPascalCase($this->table)
        ));

        $fileText .= \York\Template\Parser::parseText($factoryText, array(
            'model' => \York\Helper\String::underscoresToPascalCase($this->table)
        ));

        file_put_contents($target, \York\Template\Parser::parseText($fileTextTemplate, array(
            'tablename' => $this->table,
            'modelname' => \York\Helper\String::underscoresToPascalCase($this->table),
            'classmembers' => $fileText
        )));

        return $this;
    }

    /**
     * generates the model class file
     * overwrites the eventually found file if wanted
     *
     * @param boolean $overwrite
     *
     * @return $this
     */
    public function generateModel($overwrite = false)
    {
        $target = $this->getPathForModel();
        $targetPath = $this->getPathForModel(false);
        new \York\FileSystem\Directory($targetPath, true);

        if (true === file_exists($target)) {
            if (false === $overwrite) {
                return $this;
            }

            unlink($target);
        }

        file_put_contents($target, \York\Template\Parser::parseFile(__DIR__ . '/Generator/skeleton_model', array(
            'modelname' => $this->model
        )));

        return $this;
    }

    /**
     * @param boolean $overwrite
     *
     * @return $this
     */
    public function generateManagerBlueprint($overwrite = true)
    {
        $target = $this->getPathForManagerBlueprint();
        $targetPath = $this->getPathForManagerBlueprint(false);

        new \York\FileSystem\Directory($targetPath, true);

        if (true === file_exists($target)) {
            if (false === $overwrite) {
                return $this;
            }

            unlink($target);
        }

        file_put_contents($target, \York\Template\Parser::parseFile(__DIR__ . '/Generator/skeleton_manager_blueprint', array(
            'modelname' => $this->model,
            'table' => $this->table
        )));

        return $this;
    }

    /**
     * generates the manager class file
     * overwrites the eventually found file if wanted
     *
     * @param boolean $overwrite
     *
     * @return $this
     */
    public function generateManager($overwrite = false)
    {
        $target = $this->getPathForManager();
        $targetPath = $this->getPathForManager(false);
        new \York\FileSystem\Directory(\York\Helper\FileSystem::getDirectory($targetPath), true);

        if (true === file_exists($target)) {
            if (false === $overwrite) {
                return $this;
            }

            unlink($target);
        }

        file_put_contents($target, \York\Template\Parser::parseFile(__DIR__ . '/Generator/skeleton_manager', array(
            'modelname' => $this->model,
            'table' => $this->table
        )));

        return $this;
    }

    /**
     * @return string
     */
    public function getTable()
    {
        return $this->table;
    }

    /**
     * @param string $prefix
     *
     * @return $this
     */
    public function setPathPrefix($prefix = null)
    {
        if (null === $prefix) {
            $prefix = \York\Helper\Application::getApplicationRoot();
        }

        $this->pathPrefix = $prefix;

        return $this;
    }

    /**
     * @return string
     */
    public function getPathPrefix()
    {
        return $this->pathPrefix;
    }

    /**
     * @param null | string $infix
     * @param boolean       $full
     *
     * @return string
     */
    protected function getPath($infix = null, $full = true)
    {
        if (null === $infix) {
            $infix = '';
        } else {
            $infix .= '/';
        }

        $postfix = '';

        if (true === $full) {
            $postfix = $this->model . '.php';
        }

        return $this->getPathPrefix() . 'Model/' . $infix . $postfix;
    }

    /**
     * get the path for the model for the given table
     *
     * @param boolean $full
     *
     * @return string
     */
    public function getPathForModel($full = true)
    {
        return $this->getPath(null, $full);
    }

    /**
     * get the path for the manager for the given table
     *
     * @param boolean $full
     *
     * @return string
     */
    public function getPathForManager($full = true)
    {
        return $this->getPath('Manager', $full);
    }

    /**
     * @param boolean $full
     *
     * @return string
     */
    public function getPathForManagerBlueprint($full = true)
    {
        return $this->getPath('Manager/Blueprint', $full);
    }

    /**
     * get the path for the blueprint for the given table
     *
     * @param boolean $full
     *
     * @return string
     */
    public function getPathForBlueprint($full = true)
    {
        return $this->getPath('Blueprint', $full);
    }

    /**
     * generates models for all found tables in the current database
     *
     * @return $this
     */
    public function generateAll()
    {
        $tableSave = $this->table;

        foreach (\York\Database\Information::getAllTables(\York\Dependency\Manager::getDatabaseManager()->getConnection()->getSchema()) as $table) {
            $this->table = $table->TABLE_NAME;
            $this->generate();
        }

        $this->table = $tableSave;

        return $this;
    }
}
