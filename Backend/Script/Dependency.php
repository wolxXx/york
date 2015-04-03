<?php
namespace York\Backend\Script;

/**
 * parses the dependency configuration
 * from the application and the york framework
 * 
 * creates getters for all available dependencies 
 *
 * @package \York\Backend
 * @version $version$
 * @author  wolxXx
 */
class Dependency extends \York\Console\Application
{
    /**
     * @inheritdoc
     */
    public function help()
    {
        $this
            ->output('generates getters for all defined dependencies')
            ->output('')
            ->output('takes the default dependency configuration form york framework')
            ->output('merges it with the application side dependency configuration')
        ;
        
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function run()
    {
        $this->output('grabbing framework dependencies');
        $yorkConfiguration = new \York\FileSystem\IniFile(__DIR__.'/../../Dependency/default');
        
        $this->output('grabbing application dependencies');
        $applicationConfiguration = new \York\FileSystem\IniFile(\York\Helper\Application::getApplicationRoot().'/Configuration/dependency');
        
        $template = <<<TEMPLATE
<?php
namespace Application\Configuration;

/**
 * dependency class for shortcuts and class definitions
 *
 * @package Application\Configuration
 * @author York Framework
 * @version 1.0
 * @generatedAt %%NOW%% 
 */
class Dependency extends \York\Dependency\Manager
{
%%DEPENDENCIES%%
}
TEMPLATE;

        $configuration = \York\Helper\Set::merge($yorkConfiguration->getContent(), $applicationConfiguration->getContent());
        
        ksort($configuration);
        
        $dependencies = '';
        
        foreach($configuration as $configKey => $configValue){
            $this->output('found dependency: '.$configKey);
            $dependenciy = <<<DEPENDENCY
    
    /**
     * @return %%CLASSNAME%%
     */
    public static function get%%DEPENDENCYGETTER%%()
    {
        return \York\Dependency\Manager::get('%%DEPENDENCYNAME%%');
    }
DEPENDENCY;

            $dependenciy = \York\Template\Parser::parseText($dependenciy, array(
                'CLASSNAME'         => str_replace('\'', '', $configValue['class']),
                'DEPENDENCYGETTER'  => ucfirst($configKey),
                'DEPENDENCYNAME'    => $configKey,
            ));
            
            $dependencies .= $dependenciy;
        }

        $template = \York\Template\Parser::parseText($template, array(
            'DEPENDENCIES' => $dependencies,
            'NOW' => \York\Helper\Date::getDateTime()->format('Y-m-d H:i:s')
        ));
        
        $this->output('writing dependency class to '.\York\Helper\Application::getApplicationRoot().'Configuration/Dependency.php');
        file_put_contents(\York\Helper\Application::getApplicationRoot().'Configuration/Dependency.php', $template);
        
        return $this;
    }
}
