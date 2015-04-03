<?php
namespace York\Backend\Script\Module;

/**
 * Controller Generation Class 
 * 
 * @package York\Backend\Script\Module
 * @version $version$
 * @author  wolxXx
 */
class Controller extends \York\Backend\Script\Module\ModuleAbstract
{
    
    /**
     * @inheritdoc
     */
    public function help()
    {
        return $this;
    }
    
    /**
     * @inheritdoc
     */
    public function run()
    {
        $controller = \York\Console\Parameter::Factory('controller', 'c', true);
        $actions    = \York\Console\Parameter::Factory('actions', 'a', false, array());
        $layouts    = \York\Console\Parameter::Factory('layouts', 'l', false, array());
        $protect    = \York\Console\Flag::Factory('protect', 'p');
        
        \York\Autoload\Manager::isLoadable(sprintf('\Application\Controller\%s'));
    
    
        /**
         * check if controller already exists
         * if not, create him
         * if yes, error exit
         * 
         * 
         * check if layout directory exists
         * if not, create it
         * if yes, go on
         * 
         * create controller directory in layout path
         * create all actions as empty view script
         */
        
        \York\Helper\Application::dieDebug($controller, $actions, $layouts, $protect);
        
        return $this;
    }
    
    /**
     * @return string[]
     */
    public function getOptions()
    {
        return [
            '--controller   $controllerName',
            '--actions      $actionName1[,$actionNameN]*    => default: index',
            '--layouts      [$layoutName1[, $layoutNameN]*] => default: main',
            '--protect                                      => default: false'
        ];
    }
    
    /**
     * @return string[]
     */
    public function getDescription()
    {
        $description = <<<DESCRIPTION
create controller with views in layout

provide controller name for the controller.

provide action names as comma separated values for the actions you want to create

provide the layout name for the desired layout name
if layout name is csv string, the views will be created in all provided layouts

provide protect flag for protecting the newly created actions
DESCRIPTION;

        return explode(PHP_EOL, trim($description));
    }
}