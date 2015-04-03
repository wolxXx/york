<?php
namespace York\Backend\Script;

/**
 * creates controllers, actions, views, layouts 
 *
 * @package \York\Backend
 * @version $version$
 * @author  wolxXx
 */
class Controller extends \York\Console\Application
{
    const SCRIPT_NAME = 'creates controllers, actions, views, layouts';
    
    const SCRIPT_VERSION = '0.1';
    
    /**
     * @var string
     */
    protected $controller;
    
    /**
     * @var string[]
     */
    protected $actions;
    
    /**
     * @var string[]
     */
    protected $views;
    
    /**
     * @var string[]
     */
    protected $layouts;
    
    /**
     * @inheritdoc
     */
    public function help()
    {
        $this
            ->output('creates controllers, actions, views, layouts')
            ->newLine()
            ->output('commands:')
            ->output('--controller=$name')
            ->output('--actions=$name1,$name2')
            ->output('--layouts=$name1,$name2')
            ->output('--views=$name1,$name2')
        ;
        
        return $this;
    }
    
    /**
     * grab the parameters and flags
     * 
     * @return $this
     * @throws \York\Exception\Console
     */
    protected function grabParametersAndFlags()
    {
        $this->controller = ucfirst(\York\Console\Parameter::Factory('controller', '', true)->getValue());
        $this
            ->handleArrayParameter('actions')
            ->handleArrayParameter('layouts')
            ->handleArrayParameter('views')
        ;
        
        return $this;
    }
    
    /**
     * @param string $key
     *
     * @return $this
     */
    protected function handleArrayParameter($key) 
    {
        $this->$key = [];
        $items = [];
        
        foreach(array_unique(explode(',', \York\Console\Parameter::Factory($key, '', false, '')->getValue())) as $item){
            if('' === $item){
                continue;
            }
            $items[] = $item;
        }
        
        $this->$key = $items;
        
        return $this;
    }
    
    /**
     * @inheritdoc
     */
    public function run()
    {
        try {
            $this->grabParametersAndFlags();    
        } catch (\York\Exception\Console $exception) {
            $this
                ->errorOutput('error: please provide all required parameters!')
                ->errorOutput($exception->getMessage())
                ->output('stopping here')
            ;
            
            return $this;
        }
        try {
            $this
                ->handleController()
                ->handleActions()
                ->handleLayouts()
                ->handleViews()
            ;
        } catch (\York\Exception\Console $exception) {
            $this
                ->errorOutput('error: something went terribly wrong!')
                ->errorOutput($exception->getMessage())
                ->output('stopping here')
            ;
            
            return $this;
        }
        
        return $this;
    }
    
    /**
     * @return $this
     */
    protected function createController()
    {
        $controllerTemplate = <<<CONTROLLER
<?php
namespace Application\Controller;

/**
 * %%CONTROLLERNAME%% controller class
 *
 * @package Application\Controller 
 * @version 1.0
 * @author  York Framework
 */
class %%CONTROLLERNAME%% extends \York\Controller
{
    /**
     * @inheritdoc
     */
    public function setAccessRules()
    {
        $%%NOTHING%%this->accessChecker->addRule(new \York\AccessCheck\Rule('*'));
    }

    /**
     * index action
     * 
     * @return $%%NOTHING%%this
     */
    public function indexAction()
    {
        return $%%NOTHING%%this;
    }
}
CONTROLLER;
        
        $controllerTemplate = \York\Template\Parser::parseText($controllerTemplate, array(
            'CONTROLLERNAME'    => $this->controller,
            'NOTHING'           => ''
        ));
        
        file_put_contents(sprintf(\York\Helper\Application::getApplicationRoot().'Controller/%s.php', $this->controller), $controllerTemplate);

        return $this;
    }
    
    /**
     * @return $this
     */
    protected function handleController()
    {
        $this->verboseOutput(sprintf('check if controller "%s" exists and create if not', $this->controller));
        
        if (true === \York\Autoload\Manager::isLoadable(sprintf('\Application\Controller\%s', $this->controller))) {
            $this->verboseOutput('controller found. no need to do something..');
            
            return $this;
        }
        
        $this->verboseOutput('controller not found. a very fresh one will be created, dude!');
        
        $this->createController();
        
        return $this;
    }
    
    /**
     * @return $this
     */
    protected function handleActions()
    {
        $this->verboseOutput('check if controller has actions, create if not');
        
        $actionTemplate = <<<TEMPLATE


    /**
     * %%ACTIONNAME%% action
     * 
     * @return $%%NOTHING%%this
     */
    public function %%ACTIONNAME%%Action()
    {
        return $%%NOTHING%%this;
    }
TEMPLATE;

        
        $actionText = '';
        
        $reflection = new \ReflectionClass(sprintf('\Application\Controller\%s', $this->controller));
        
        foreach ($this->actions as $action) {
            //check reflection if action exists
            $method = null;
            try{
                $method = $reflection->getMethod($action.'Action');
            }catch (\Exception $exception) {
                $method = null;
            }
            
            if(null !== $method) {
                $this->verboseOutput(sprintf('action "%s" already found. ignoring.', $action));
                
                continue;
            }
            
            
            $actionText .= \York\Template\Parser::parseText($actionTemplate, array(
                'ACTIONNAME'    => $action,
                'NOTHING'       => ''
            ));
            
            $this->verboseOutput(sprintf('action "%s" added.', $action));
        }

        $controllerContent = file_get_contents(sprintf(\York\Helper\Application::getApplicationRoot().'Controller/%s.php', $this->controller));
        
        $controllerContent = rtrim($controllerContent);
        $controllerContent = rtrim($controllerContent, '}');
        
        $controllerContent .= $actionText;
        $controllerContent .= PHP_EOL.'}';
        
        $this->verboseOutput('writing contents to controller class file');
        
        file_put_contents(sprintf(\York\Helper\Application::getApplicationRoot().'Controller/%s.php', $this->controller), $controllerContent);
        
        return $this;
    }
    
    /**
     * @return $this
     */
    protected function handleViews()
    {
        $this->verboseOutput('check if views exist, create if not');  
        
        $viewTemplate = <<<TEMPLATE
<?
/**
 * @var \York\View\Manager $%%NOTHING%%this
 */
?>

<h1>Autogenerated view</h1>
<p>
    please change me!
</p>
TEMPLATE;
        
        //Application/View/$LAYOUT/$CONTROLLER/$VIEW
        
        foreach ($this->layouts as $layout) {
            foreach ($this->views as $view) {
                $target = sprintf('%s%s/%s/%s.php', \York\Helper\Application::getApplicationRoot(), ucfirst($layout), ucfirst($this->controller), $view);
            }
        }
        
        
        return $this;
    }
    
    /**
     * @return $this
     */
    protected function handleLayouts()
    {
        $this->verboseOutput('check if layouts exist, create if not');
        
        $layoutTemplate = <<<TEMPLATE
<?php
/**
 * @var string $%%NOTHING%%content
 */
$%%NOTHING%%title = isset($%%NOTHING%%title) ? $%%NOTHING%%title . ' - ' : '';
$%%NOTHING%%title .= \York\Dependency\Manager::getApplicationConfiguration()->getSafely('app_name', 'MyPage');

\Application\Configuration\Dependency::getAssetManager()
    ->addCssFile('/css/styles.css')
    ->addJavaScriptFile('/js/mylib.js')
;

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="content-type" content="text/html; charset=utf-8">
    <link type="text/plain" rel="author" href="/humans.txt">
    <link rel="SHORTCUT ICON" href="/favicon.ico">
    <meta name="description" content="awesome page">
    <meta name="keywords" content="awesome page contents>">
    <title><?php echo $%%NOTHING%%title ?></title>
    <?php echo \Application\Configuration\Dependency::getAssetManager()->getLess(); ?>
    <?php echo \Application\Configuration\Dependency::getAssetManager()->getCss(); ?>
</head>
<body>
    <?php echo $%%NOTHING%%content ?>
    <div id="sources">
        <?php echo \Application\Configuration\Dependency::getAssetManager()->getJavaScript(); ?>
    </div>
</body>
TEMPLATE;

        $layoutContent = \York\Template\Parser::parseText($layoutTemplate, array(
            'NOTHING' => ''
        ));
        
        foreach ($this->layouts as $layout) {
            $target = \York\Helper\Application::getApplicationRoot().'View/Layout/'.$layout.'.php';
            
            if (true === file_exists($target)) {
                $this->verboseOutput('layout file already exists for layout '.$layout);
                
                continue;
            }
            
            $this->verboseOutput('creating layout file for '.$layout);
            \York\FileSystem\Directory::Factory(\York\Helper\Application::getApplicationRoot().'View/Layout', true);
            file_put_contents($target, $layoutContent);
        }
        
        return $this;
    }
}
