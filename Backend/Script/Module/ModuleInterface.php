<?php
namespace York\Backend\Script\Module;

/**
 * Module Class Interface
 * 
 * @package York\Backend\Script\Module
 * @version $version$
 * @author  wolxXx
 */
interface ModuleInterface
{
    /**
     * @return $this
     */
    public function help();
    
    /**
     * @return string[]
     */
    public function getOptions();
    
    /**
     * @return string[]
     */
    public function getDescription();
    
    /**
     * @return $this
     */
    public function run();
}
