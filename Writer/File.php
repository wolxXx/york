<?php
namespace York\Writer;

/**
 * write something into a file
 *
 * @package \York\Writer
 * @version $version$
 * @author wolxXx
 */
class File implements WriterInterface
{
    /**
     * path to the desired file
     *
     * @var string
     */
    protected $path;

    /**
     * setup
     * @param string $path
     */
    public function __construct($path = null)
    {
        $this->setPath($path);
    }

    /**
     * @inheritdoc
     */
    public static function Factory()
    {
        return new self();
    }

    /**
     * setter for the path
     *
     * @param string $path
     *
     * @return $this
     */
    public function setPath($path)
    {
        $this->path = $path;

        if (null === $this->path) {
            $this->path = \York\Helper\Application::getProjectRoot() . 'log/writerOutput';
        }

        return $this;
    }

    /**
     * checks if a file exists at the given path
     *
     * @return $this
     */
    protected function checkFile()
    {
        if (false === file_exists($this->path)) {
            touch($this->path);
        }

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function write($text)
    {
        $this->checkFile();
        file_put_contents($this->path, file_get_contents($this->path) . PHP_EOL . $text);

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function debug($text)
    {
        return $this->write($text);
    }

    /**
     * @inheritdoc
     */
    public function verbose($text)
    {
        return $this->write($text);
    }
}
