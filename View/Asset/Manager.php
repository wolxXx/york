<?php
namespace York\View\Asset;

/**
 * default asset manager
 *
 * @package \York\View\Asset
 * @version $version$
 * @author wolxXx
 */
class Manager implements \York\View\Asset\ManagerInterface
{
    /**
     * @var string
     */
    protected $javaScriptText;

    /**
     * @var string[]
     */
    protected $javaScriptFiles;

    /**
     * @var string
     */
    protected $cssText;

    /**
     * @var string[]
     */
    protected $cssFiles;

    /**
     * @var string[]
     */
    protected $lessFiles;

    /**
     * @var string
     */
    protected $pathToLessParser;

    /**
     * factory function
     *
     * @return $this
     */
    public static function Factory()
    {
        return new self();
    }

    /**
     * constructor
     */
    public function __construct()
    {
        $this->clear();
    }

    /**
     * @return string
     */
    public function getAll()
    {
        return $this->getJavaScript() . $this->getCss() . $this->getLess();
    }

    /**
     * @return $this
     */
    public function display()
    {
        echo $this->getAll();

        return $this;
    }

    /**
     * retrieves the last edit date of the given file
     *
     * needed for browser cache handling and unit testing
     *
     * @param string $file
     *
     * @return integer
     *
     * @codeCoverageIgnore
     *
     * @todo implement me!
     */
    protected function getTimeStamp($file)
    {
        return time();
    }

    /**
     * @param array     $array
     * @param string    $value
     * @param boolean   $top
     *
     * @return array
     */
    protected function mergeFileIntoArray($array, $value, $top = false)
    {
        if (true === $top) {
            $array = \York\Helper\Set::removeValue($array, $value);
            array_unshift($array, $value);

            return $array;
        }

        if (false === in_array($value, $array)) {
            $array[] = $value;
        }

        return $array;
    }

    /**
     * @param string    $text
     * @param string    $additionalText
     * @param boolean   $top
     *
     * @return string
     */
    protected function mergeTextIntoText($text, $additionalText, $top = false)
    {
        if (true === $top) {
            return $additionalText . PHP_EOL . $text;
        }

        return $text . PHP_EOL . $additionalText;
    }

    /**
     * @inheritdoc
     */
    public function clear()
    {
        $this
            ->clearJavaScript()
            ->clearCss()
            ->clearLess()
        ;

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function clearJavaScript()
    {
        $this
            ->clearJavaScriptFiles()
            ->clearJavaScriptText()
        ;

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function clearJavaScriptText()
    {
        $this->javaScriptText = '';

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function clearJavaScriptFiles()
    {
        $this->javaScriptFiles = array();

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function removeJavaScriptFile($path)
    {
        $this->javaScriptFiles = \York\Helper\Set::removeValue($this->javaScriptFiles, $path, true);

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function addJavaScriptFile($path, $top = false)
    {
        $this->javaScriptFiles = $this->mergeFileIntoArray($this->javaScriptFiles, $path, $top);

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function addJavaScriptFiles($paths)
    {
        foreach ($paths as $path) {
            $this->addJavaScriptFile($path);
        }

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function addJavaScriptText($text, $top = false)
    {
        $this->javaScriptText = $this->mergeTextIntoText($this->javaScriptText, $text, $top);

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function getJavaScriptText()
    {
        return $this->javaScriptText;
    }

    /**
     * @inheritdoc
     */
    public function getJavaScriptFiles()
    {
        return $this->javaScriptFiles;
    }

    /**
     * @inheritdoc
     */
    public function getJavaScript()
    {
        $javaScript = '';

        if ('' !== $this->getJavaScriptText()) {
            $javaScript = sprintf('<script>%s%s%s</script>%s', PHP_EOL, $this->getJavaScriptText(), PHP_EOL, PHP_EOL);
        }

        foreach ($this->getJavaScriptFiles() as $javaScriptFile) {
            $javaScript .= sprintf('<script src="%s?ts=%s"></script>%s', $javaScriptFile, $this->getTimeStamp($javaScriptFile), PHP_EOL);
        }

        return $javaScript;
    }

    /**
     * @inheritdoc
     */
    public function addCssFile($path, $top = false)
    {
        $this->cssFiles = $this->mergeFileIntoArray($this->cssFiles, $path, $top);

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function addCssFiles($paths)
    {
        foreach ($paths as $path) {
            $this->addCssFile($path);
        }

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function addCssText($text, $top = false)
    {
        $this->cssText = $this->mergeTextIntoText($this->cssText, $text, $top);

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function clearCss()
    {
        return $this
            ->clearCssText()
            ->clearCssFiles();
    }

    /**
     * @inheritdoc
     */
    public function clearCssText()
    {
        $this->cssText = '';

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function clearCssFiles()
    {
        $this->cssFiles = array();

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function removeCssFile($path)
    {
        $this->cssFiles = \York\Helper\Set::removeValue($this->cssFiles, $path);

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function getCssText()
    {
        return $this->cssText;
    }

    /**
     * @inheritdoc
     */
    public function getCssFiles()
    {
        return $this->cssFiles;
    }

    /**
     * @inheritdoc
     */
    public function getCss()
    {
        $css = '';

        if ('' !== $this->getCssText()) {
            $css .= sprintf('<style type="text/css">%s%s%s</style>', PHP_EOL, $this->getCssText(), PHP_EOL);
        }

        foreach ($this->getCssFiles() as $cssFile) {
            $css .= sprintf('%s<link rel="stylesheet" type="text/css" href="%s?ts=%s">', PHP_EOL, $cssFile, $this->getTimeStamp($cssFile));
        }

        return $css;
    }

    /**
     * @inheritdoc
     */
    public function addLessFile($path, $top = false)
    {
        $this->lessFiles = $this->mergeFileIntoArray($this->lessFiles, $path, $top);

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function addLessFiles($paths)
    {
        foreach ($paths as $path) {
            $this->addLessFile($path);
        }

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function clearLess()
    {
        $this->lessFiles = array();

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function removeLessFile($path)
    {
        $this->lessFiles = \York\Helper\Set::removeValue($this->lessFiles, $path);

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function getLessFiles()
    {
        return $this->lessFiles;
    }

    /**
     * @inheritdoc
     */
    public function getLess()
    {
        if (true === empty($this->getLessFiles())) {
            return '';
        }

        $less = '';

        foreach ($this->getLessFiles() as $lessFile) {
            $less .= sprintf('<link rel="stylesheet/less" type="text/css" href="%s?ts=%s" />%s', $lessFile, $this->getTimeStamp($lessFile), PHP_EOL);
        }

        $less .= <<<LESS

<script type="text/javascript">
	less = {
		env: "development", // or "production"
		async: false,	   // load imports async
		fileAsync: false,   // load imports async when in a page under
		poll: 1000,		 // when in watch mode, time in ms between polls
		functions: {},	  // user functions, keyed by name
		dumpLineNumbers: "all", // or "mediaQuery" or "all"
		relativeUrls: true,// whether to adjust url's to be relative
	};
</script>
LESS;

        $less .= sprintf('<script src="%s?ts=%s" type="text/javascript"></script>%s', $this->getPathToLessParser(), $this->getTimeStamp($this->getPathToLessParser()), PHP_EOL);

        return $less;
    }

    /**
     * @inheritdoc
     */
    public function setPathToLessParser($path = null)
    {
        if (null === $path) {
            $path = '/js/less.js';
        }

        $this->pathToLessParser = $path;

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function getPathToLessParser()
    {
        if (null === $this->pathToLessParser) {
            return '/js/less.js';
        }

        return $this->pathToLessParser;
    }
}
