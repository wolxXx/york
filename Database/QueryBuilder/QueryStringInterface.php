<?php
namespace York\Database\QueryBuilder;

/**
 * the core model accepts only a query string
 * that was made by query builders
 *
 * @package York\Database\QueryBuilder
 * @version $version$
 * @author wolxXx
 */
interface QueryStringInterface
{
    /**
     *constructor
     *
     * @param string $queryString
     */
    public function __construct($queryString = null);

    /**
     * getter for the query string
     *
     * @param boolean $cleared
     *
     * @return string
     */
    public function getQueryString($cleared = false);


    /**
     * setter for the query string
     *
     * @param string
     */
    public function setQueryString($string);
}
