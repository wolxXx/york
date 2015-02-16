<?php
namespace York\View\Asset;

/**
 * interface definition for the asset manager
 *
 * @package \York\View\Asset
 * @version $version$
 * @author wolxXx
 */
interface ManagerInterface
{
    /**
     * clears all added resources (js, less, css)
     *
     * @return $this
     */
    public function clear();

    /**
     * adds a JavaScript file
     *
     * @param string    $path
     * @param boolean   $top
     *
     * @return $this
     */
    public function addJavaScriptFile($path, $top = false);

    /**
     * adds several JavaScript files
     *
     * @param string[] $paths
     *
     * @return mixed
     */
    public function addJavaScriptFiles($paths);

    /**
     * adds JavaScript text
     *
     * @param string    $text
     * @param boolean   $top
     *
     * @return $this
     */
    public function addJavaScriptText($text, $top = false);

    /**
     * clears all JavaScript resources (files and text)
     *
     * @return $this
     */
    public function clearJavaScript();

    /**
     * clears the JavaScript text
     *
     * @return $this
     */
    public function clearJavaScriptText();

    /**
     * clears all JavaScript files
     *
     * @return $this
     */
    public function clearJavaScriptFiles();

    /**
     * removes the specified JavaScript file
     *
     * @param string $path
     *
     * @return $this
     */
    public function removeJavaScriptFile($path);

    /**
     * returns the set JavaScript text
     *
     * @return string
     */
    public function getJavaScriptText();

    /**
     * returns the set JavaScript files
     *
     * @return string[]
     */
    public function getJavaScriptFiles();

    /**
     * generates the JavaScript part
     * wraps the files into "<script src=""></script>"
     * wraps the text into "<script></script>"
     *
     * @return string
     */
    public function getJavaScript();

    /**
     * adds a css file
     *
     * @param string    $path
     * @param boolean   $top
     *
     * @return $this
     */
    public function addCssFile($path, $top = false);

    /**
     * adds several css files
     *
     * @param string[] $paths
     *
     * @return $this
     */
    public function addCssFiles($paths);

    /**
     * adds css text
     *
     * @param string    $text
     * @param boolean   $top
     *
     * @return $this
     */
    public function addCssText($text, $top = false);

    /**
     * clears all css resources (files, text)
     *
     * @return $this
     */
    public function clearCss();

    /**
     * clears the css text
     *
     * @return $this
     */
    public function clearCssText();

    /**
     * clears all css files
     *
     * @return $this
     */
    public function clearCssFiles();

    /**
     * removes the specified css file
     *
     * @param string $path
     *
     * @return $this
     */
    public function removeCssFile($path);

    /**
     * returns the set css text
     *
     * @return string
     */
    public function getCssText();

    /**
     * returns the set css files
     *
     * @return string[]
     */
    public function getCssFiles();

    /**
     * generates the css part
     * wraps the files into "<link rel="stylesheet" type="text/css" href="">"
     * wraps the text into <style type="text/css"></style>
     *
     * @return string
     */
    public function getCss();

    /**
     * @param string $path
     *
     * @return $this
     */
    public function setPathToLessParser($path);

    /**
     * @return string
     */
    public function getPathToLessParser();

    /**
     * adds a less file
     *
     * @param string    $path
     * @param boolean   $top
     *
     * @return $this
     */
    public function addLessFile($path, $top = false);

    /**
     * adds several less files
     *
     * @param string[] $paths
     *
     * @return $this
     */
    public function addLessFiles($paths);

    /**
     * clears the less resources
     *
     * @return $this
     */
    public function clearLess();

    /**
     * removes the specified less file
     *
     * @param string $path
     *
     * @return $this
     */
    public function removeLessFile($path);

    /**
     * returns the set less files
     *
     * @return $this
     */
    public function getLessFiles();

    /**
     * generates the less part
     * wraps the less files into <link rel="stylesheet/less" type="text/css" href="" />
     * sets the requested configuration for the less compiler
     * calls <script src="/js/vendor/less/less.js"></script>
     * be aware to provide the less compiler at the requested place
     * or add your specific less compiler via $this->addJavaScriptFiles()
     *
     * @return string
     */
    public function getLess();
}
