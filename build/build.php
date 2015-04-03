#! /usr/bin/php
<?php

/**
 * setup class
 *
 * @package \York\build
 * @version 1.0
 * @author wolxXx
 */
class Setup
{
    /**
     * @var boolean
     */
    protected $force = false;

    /**
     * @var boolean
     */
    protected $help = false;

    /**
     * @var false
     */
    protected $zip = false;

    /**
     * @var false
     */
    protected $tar = false;

    /**
     * @var null | string
     */
    protected $version = null;

    /**
     * @var null | string
     */
    protected $readVersion = null;

    /**
     * @var null | string
     */
    protected $target = null;

    /**
     * @var null | string
     */
    protected $tmpTarget = null;

    /**
     * @var null | string
     */
    protected $source = null;

    /**
     * setup, run and exit..
     */
    public function __construct()
    {
        ini_alter('xdebug.var_display_max_data', '1000000');
        ini_alter('xdebug.var_display_max_children', '1000000');
        ini_alter('xdebug.var_display_max_depth', '1000000');

        $this->run();

        exit(0);
    }

    /**
     * running mechanism
     * calls each build step
     *
     * @return $this
     */
    protected function run()
    {
        return $this
            ->clearScreen()
            ->showHeader()
            ->newLine()
            ->parseParameters()
            ->checkGitStatus()
            ->getVersion()
            ->copySources()
            ->processFiles()
            ->packIfNeeded()
            ->output('done...')
            ->output('please check in this new awesome version!')
            ->newLine()
            ->newLine()
            ->output('git branch ');
    }

    /**
     * retrieve the version
     *
     * @return $this
     */
    protected function getVersion()
    {
        $this->readVersion = null;

        if (null !== $this->version) {
            $this->output(sprintf('version via arg provided: %s', $this->version));
            $this->readVersion = $this->version;
        } else {
            $this->readVersion = readline('version: ');
        }

        $now = new DateTime();
        $this->version = $this->readVersion . ' | built at ' . $now->format('Y-m-d H:i:s');

        $this->output(sprintf('version: %s', $this->version));

        return $this;
    }

    /**
     * check given parameters
     *
     * @return $this
     */
    protected function parseParameters()
    {
        $flags = array(
            'help',
            'force',
            'zip',
            'tar'
        );

        foreach ($flags as $flag) {
            if (true === isset(getopt('', array($flag))[$flag])) {
                $this->$flag = true;
            }
        }

        if (true === isset(getopt('', array('version:'))['version'])) {
            $this->version = getopt('', array('version:'))['version'];
        }

        if (true === $this->help) {
            $this->showHelp();
            $this->newLine();

            exit(0);
        }

        return $this;
    }

    /**
     * copy the needed sources, remove build or dev stuff
     *
     * @return $this
     */
    protected function copySources()
    {
        /**
         * walk along all files under __DIR__.'/../ and substitute $version$ with arg1
         */

        $this->target = __DIR__ . '/dist/version/' . $this->readVersion;
        $this->tmpTarget = __DIR__ . '/../../york-dist';
        $this->source = __DIR__ . '/../../york';

        $this
            ->output(sprintf('source: %s', $this->source))
            ->output(sprintf('target: %s', $this->target))
            ->output(sprintf('tmp target: %s', $this->tmpTarget));

        if (is_dir($this->target)) {
            $this->execute(sprintf('rm -rf %s', $this->target), 'target exists, clearing');
        }

        if (is_dir($this->tmpTarget)) {
            $this->execute(sprintf('rm -rf %s', $this->tmpTarget), 'tmp target exists, clearing');
        }

        $this->execute(sprintf('mkdir -p %s', $this->target), 'creating target directory');
        $this->execute(sprintf('rm -rf %s', $this->target), 'clean up target');
        $this->execute(sprintf('cp -r %s %s', $this->source, $this->tmpTarget), 'copying source to tmp target');
        $this->execute(sprintf('mv %s %s', $this->tmpTarget, $this->target), 'moving tmp target to target');


        $this->execute(sprintf('rm -rf %s/build', $this->target), 'removing dev dirs and files: build');
        $this->execute(sprintf('rm -rf %s/.git', $this->target), 'removing dev dirs and files: git');
        $this->execute(sprintf('rm -rf %s/.idea', $this->target), 'removing dev dirs and files: idea');
        $this->execute(sprintf('rm -rf %s/Test', $this->target), 'removing dev dirs and files: Test');
        $this->execute(sprintf('rm -rf %s/vendor', $this->target), 'removing dev dirs and files: Test');

        return $this;
    }

    /**
     * clearing the screen
     *
     * @return $this
     */
    protected function clearScreen()
    {
        system('clear');

        return $this;
    }

    /**
     * print the header
     *
     * @return $this
     */
    function showHeader()
    {
        echo str_replace('%%nameAndVersion%%', 'build y0rk 1.0', file_get_contents(__DIR__ . '/../Console/header'));

        return $this;
    }

    /**
     * display the help text and the possible parameters
     *
     * @return $this
     */
    function showHelp()
    {
        $this
            ->output('y0rk build tool')
            ->newLine()
            ->output('runs through the framework, sets version, corrects some mistakes (namespaces, php short open tags.. )')
            ->newLine()
            ->output('params:')
            ->output('--help: this help')
            ->output('--zip: zip the build result')
            ->output('--tar: tar and zip the build result')
            ->output('--force: ignore untracked / changed files in git repo')
            ->output('--version $version: provided version name, if not provided, you will be asked later...');

        return $this;
    }

    /**
     * print the given text
     *
     * @param string $text
     *
     * @return $this
     */
    function output($text = '')
    {
        echo $text . PHP_EOL;

        return $this;
    }

    /**
     * print an empty line
     *
     * @return $this
     */
    function newLine()
    {
        return $this->output();
    }

    /**
     * print the given comment or nothing if comment is null
     * execute the given command
     *
     * @param               $command
     * @param null | string $comment
     *
     * @return string
     */
    function execute($command, $comment = null)
    {
        if (null !== $comment) {
            $this->output($comment . '...');
        }

        exec($command, $output);

        return $output;
    }

    /**
     * process the copied files
     * corrects some mistakes (namespaces, php short open tags.. )
     *
     * @return $this
     */
    protected function processFiles()
    {

        $children = array();
        $files = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($this->target), \RecursiveIteratorIterator::SELF_FIRST);

        $this->output('grabbing files...');

        foreach (array_keys(iterator_to_array($files, true)) as $current) {
            if (true === is_dir($current)) {
                continue;
            }

            $children[] = $current;
        }

        $this->output('processing files...');

        foreach ($children as $current) {
            if ('text' !== explode('/', mime_content_type($current))[0]) {
                continue;
            }

            $content = file_get_contents($current);
            $replaces = array(
                '$version$' => $this->version,
                '@package \York' => '@package York',
                '<?= ' => '<?php echo ',
                '<? ' => '<?php ',
                '<?' . PHP_EOL => '<?php' . PHP_EOL,
            );

            foreach ($replaces as $key => $value) {
                $content = str_replace($key, $value, $content);
            }

            file_put_contents($current, $content);
        }

        return $this;
    }

    protected function packIfNeeded()
    {
        if (true === $this->tar) {

            return $this;
        }

        if(true === $this->zip){
            chdir($this->target.'/../');
            $this->execute(sprintf('zip -mr %s.zip %s', $this->target, $this->readVersion), sprintf('zipping build result into %s.zip', $this->target));
            chdir(__DIR__);

            return $this;
        }

        return $this;
    }

    /**
     * checking the status of the current git workspace / branch
     *
     * @return $this
     */
    function checkGitStatus()
    {
        exec('git status --porcelain', $changes, $result);
        $changedFiles = array();

        foreach ($changes as $change) {
            if (0 === strpos($change, ' M ')) {
                $changedFiles[] = $change;
            }
        }

        if (true === $this->force) {
            return $this->output('warning: you have changed files!!');
        }

        if (false === empty($changedFiles)) {
            $this
                ->newLine()
                ->output('[ERROR]: can not proceed.')
                ->output('you have untracked changes in the repository.')
                ->output('please commit and push your changes first!!')
                ->newLine()
                ->output('or provide --force parameter!')
                ->newLine()
                ->output('here is the list of changed files:');

            foreach ($changedFiles as $changedFile) {
                $this->output($changedFile);
            }

            $this
                ->newLine()
                ->output('quiting here...')
                ->newLine();

            exit(1);
        }

        return $this;
    }
}

new Setup();
