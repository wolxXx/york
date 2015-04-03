<?php
namespace York\Backend\Script;

/**
 * york cli interface
 * provides application modifications
 * like migrations, model generation, controller adding etc
 * 
 * modules come from York\Backend\Script\Module namespace
 *
 * @package York\Backend\Script
 * @version $version$
 * @author  wolxXx
 */
class York extends \York\Console\Application
{
    const SCRIPT_NAME = 'create and generate migrations';
    
    const SCRIPT_VERSION = '$version$';
    
    /**
     * @inheritdoc
     */
    public function help()
    {
        /**
         * 
         * patterns:
         *      - york.php  
         *      - york.php --help
         *          -> lists all modules
         *      - york.php --help $moduleName#
         *          -> lists all actions from module $moduleName
         *      - york.php --help $moduleName $action
         *          -> lists the help of the action $action in module $moduleName
         * 
         * check if param is given
         * 
         * if no param is defined
         */
        foreach (explode(PHP_EOL, \York\FileSystem\File::Factory(__DIR__ . '/help/york')->getContent()) as $line) {
            $this->output($line);
        }

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function run()
    {
        if(true === \York\Console\Flag::Factory('dependency')->isEnabled()) {
            new Dependency();
        }
        
        if(true === \York\Console\Flag::Factory('model')->isEnabled()) {
            new ModelGenerator();
        }
        
        if(true === \York\Console\Flag::Factory('migration')->isEnabled()) {
            new Migrator();
        }
        
        if(true === \York\Console\Flag::Factory('controller')->isEnabled()) {
            new \York\Backend\Script\Module\Controller();
        }
        
        $this->help();

        return $this;
    }
}
