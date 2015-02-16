<?php
namespace York\Template;

/**
 * simple template parser
 *
 * @package \York\Template
 * @version $version$
 * @author wolxXx
 */
class Parser
{
    /**
     * the delimiter for placeholders
     */
    const DELIMITER = '%%';

    /**
     * get the content of the requested file
     *
     * @param string $file
     *
     * @return string
     *
     * @throws \York\Exception\FileSystem
     */
    protected static function getTemplateContent($file)
    {
        return \York\FileSystem\File::Factory($file)->getContent();
    }

    /**
     * parses the content of the given file
     *
     * @param string        $file
     * @param array         $params
     * @param string | null $delimiter
     *
     * @return string
     */
    public static function parseFile($file, array $params, $delimiter = null)
    {
        return self::parse(self::getTemplateContent($file), $params, $delimiter);
    }

    /**
     * parses the given text
     *
     * @param string        $text
     * @param array         $params
     * @param string | null $delimiter
     *
     * @return string
     */
    public static function parseText($text, array $params, $delimiter = null)
    {
        return self::parse($text, $params, $delimiter);
    }

    /**
     * replace the placeholders with the given params
     *
     * @param string    $content
     * @param array     $params
     * @param null      $delimiter
     *
     * @return string
     */
    protected static function parse($content, array $params, $delimiter = null)
    {
        if (null === $delimiter) {
            $delimiter = self::DELIMITER;
        }

        foreach ($params as $key => $value) {
            $content = str_replace($delimiter . $key . $delimiter, $value, $content);
        }

        return $content;
    }
}
