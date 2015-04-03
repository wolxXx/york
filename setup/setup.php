#! /usr/bin/php
<?php
namespace York\Setup;

try{
    require_once(__DIR__.'/../Backend/Bootstrap.php');
}catch (\Exception $unneededexception){
    //noop...
}

/**
 * setup class
 *
 * @package York\Setup
 * @author wolxXx
 * @version 1.0
 */
class Setup extends \York\Console\Application{
    /**
     * @var \York\FileSystem\Directory
     */
    protected $target;

    /**
     * @var \York\FileSystem\Directory
     */
    protected $here;

    /***
     * must be implemented by extending class
     * shall display usage and help text
     */
    public function help()
    {
        return $this
            ->output('foobar!')
            ->output('help comming soon...')
        ;
    }

    /**
     * @inheritdoc
     */
    public function run()
    {
        ini_alter('xdebug.var_display_max_data', '1000000');
        ini_alter('xdebug.var_display_max_children', '1000000');
        ini_alter('xdebug.var_display_max_depth', '1000000');
        return $this
            ->welcomeAgain()
            ->getTarget()
            ->setTarget()
            ->createDirectories()
            ->createSymLinks()
            ->copySourceFiles()
            ->goodBye()
        ;
    }

    /**
     * @return $this
     */
    protected function copySourceFiles()
    {
        $this->output('copy source files.');
        $files = array(
            'index.php' => 'docroot/index.php'
        );

        $src = new \York\FileSystem\Directory(__DIR__.'/src');

        foreach ($src->getChildren() as $child) {
            if (true === $child instanceof \York\FileSystem\Directory) {
                continue;
            }

            $target = $this->target->getFullPath().str_replace(__DIR__.'/src/', '', $child->getPath());

            if (true === file_exists($target)) {
                $this->output($target.': exists');

                continue;
            }

            $child->copy($target);
            $this->output($target.': copied');
        }

        return $this;
    }

    /**
     * @return $this
     *
     * @throws \York\Exception\FileSystem
     */
    protected function createSymLinks()
    {
        $links = array(
            'Backend/models.php'        => 'cli/models.php',
            'Backend/migrations.php'    => 'cli/migrations.php',
            'Backend/york.php'          => 'cli/york.php'
        );

        foreach ($links as $source => $target) {
            $source = __DIR__.'/../'.$source;
            $target = $this->target->getFullPath().$target;

            if (true === file_exists($target)) {
                $this->output($target.': exists');

                continue;
            }

            $call = new \York\Console\SystemCall(sprintf('ln -s %s %s', $source, $target));
            $call->run();

            if (false === file_exists($target)) {
                $message = sprintf('could not create symlink from %s to %s', $source, $target);;

                throw new \York\Exception\FileSystem($message);
            }

            $this->output($target.': created sym link');
        }

        return $this->newLine();
    }

    /**
     * @return $this
     */
    protected function createDirectories()
    {
        $this->output('creating directories.');
        $directories = array(
            'Application',
            'Application/Configuration',
            'Application/Controller',
            'Application/Migration',
            'Application/Model/Blueprint/Manager',
            'Application/setup',
            'Application/View/Layout',
            'Application/View/Main/cms',
            'cli',
            'docroot/js/vendor',
            'docroot/css',
            'docroot/img',
            'files',
            'log',
            'tmp',
        );

        foreach ($directories as $directory) {
            $directory = $this->target->getFullPath().$directory;

            if (true === is_dir($directory)) {
                $this->output($directory.': exists');

                continue;
            }

            new \York\FileSystem\Directory($directory, true);

            $this->output($directory.': created');
        }

        $this->newLine();

        return $this;
    }

    /**
     * @return $this
     */
    protected function setTarget()
    {
        $this
            ->output('we are here: '.$this->here->getFullPath())
            ->output('proposed target: '.$this->target->getFullPath())
            ->newLine()
            ->output('where do you want to install your application? (leave blank for proposed)');

        $decision = \York\Console\Readline::Factory($this->outputPrefix.'target')->read()->getValue();

        if ('' !== $decision) {
            try{
                $this->target = new \York\FileSystem\Directory($decision, true);
            }catch (\York\Exception\FileSystem $exception){
                $this->errorOutput('can not find '.$decision);

                return $this->setTarget();
            }
        }

        return $this
            ->output('target set to '.$this->target->getFullPath())
            ->newLine();
    }

    /**
     * @return $this
     */
    protected function getTarget()
    {
        $this->output('searching for possible installation targets...');
        $this->here = new \York\FileSystem\Directory(__DIR__);
        chdir(__DIR__);
        $parent = $this->here->getParent()->getParent();

        if (true === in_array($parent->getName(), array('Library', 'vendor', 'lib', 'library'))){
            $this->target = $parent->getParent();

            return $this;
        }

        $parent->getParent();

        if ('wolxxx' === $parent->getName()){
            $this->target = $parent->getParent()->getParent();

            return $this;
        }

        $this->target = $this->here;

        return $this->newLine();
    }

    /**
     * @return $this
     */
    protected function welcomeAgain()
    {
        return $this
            ->output(sprintf('v.%s | devops@wolxXx.de | git.wolxxx.de | https://github.com/wolxXx', $this->version))
            ->output('licensed under MIT general public open source license.')
            ->output('love it, share it, extend it. improve the world!')
            ->output('________________________________________________')
            ->newLine(2)
        ;
    }

    /**
     * @return $this
     */
    protected function goodBye()
    {
        return $this
            ->output('Please change the owner of your installation directory to the owner of the webserver you are running!')
            ->output('e.g. www-data if you are using apache: chown -cR www-data:www-data '.$this->target->getFullPath())
            ->newLine()
            ->output('please change the credentials of your database connection in Application/Configuration/Host.php')
            ->output('please change the settings of your app in Application/Configuration/Application.php')
            ->newLine()
            ->output('i hope that you are enjoying the York Framework!')
            ->output('any contact, comments, error reportings, etc. is apprechiated!')
            ->output('have an eye on https://york.wolxxx.de - stay in contact at twitter: @piotr_panski')
            ->output('cya!')
            ->output()
            ->output('__________________')
        ;
    }

}

new \York\Setup\Setup('Setup', '1.0');

/**
 * foobar
 */
namespace Application\Configuration;
class Host{
    public function configureApplication(){
        return $this;
    }
    public function configureHost(){
        return $this;
    }
    public function checkConfig(){
        return $this;
    }
}
