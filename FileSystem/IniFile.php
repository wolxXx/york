<?php
namespace York\FileSystem;

/**
 * special class for ini-configuration-files
 *
 * @package York\FileSystem
 * @version $version$
 * @author wolxXx
 */
class IniFile extends File
{
    /**
     * @var array
     */
    protected $content;

    /**
     * @var boolean
     */
    protected $parsed = false;

    /**
     * @return array
     */
    public function parse()
    {
        $this->content = parse_ini_file($this->getFullName(), true, INI_SCANNER_RAW);
        $this->parsed = true;

        return $this;
    }

    /**
     * @return array
     */
    public function getContent()
    {
        if (false === $this->parsed) {
            $this->parse();
        }

        return $this->content;
    }
}
