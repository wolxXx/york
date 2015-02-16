<?php
namespace York\Database\QueryBuilder;

/**
 * just a simple query string class
 * implementing the QueryStringInterface
 *
 * @package York\Database\QueryBuilder
 * @version $version$
 * @author wolxXx
 */
class QueryString implements \York\Database\QueryBuilder\QueryStringInterface
{
    /**
     * the query string
     *
     * @var string
     */
    protected $queryString;

    /**
     * constructor
     *
     * @param string $queryString
     */
    public function __construct($queryString = null)
    {
        $this->setQueryString($queryString);
    }

    /**
     * @inheritdoc
     */
    public function setQueryString($string)
    {
        $this->queryString = $string;

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function getQueryString($cleared = false)
    {
        if (true === $cleared) {
            $this->queryString = str_replace(array("\n", "\t"), array(' ', ''), $this->queryString);

            while (false !== strstr($this->queryString, '  ')) {
                $this->queryString = str_replace('  ', ' ', $this->queryString);
            }

            $this->queryString = str_replace(' ;', ';', $this->queryString);
            $this->queryString = trim($this->queryString, ' ');
        }

        return $this->queryString;
    }
}
