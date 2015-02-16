<?php
namespace York\Database\Model;

/**
 * interface for database manager
 *
 * @package York\Database\Model
 * @version $version$
 * @author wolxXx
 */
interface ManagerInterface
{
    /**
     * find one by its id
     *
     * @param integer $id
     *
     * @return null | \York\Database\Model\Item
     */
    public function getById($id);

    /**
     * find all by the the query builder data
     *
     * @param \York\Database\QueryBuilder   $query
     *
     * @return \York\Database\Blueprint\ItemInterface[]
     */
    public function find(\York\Database\QueryBuilder $query);
}
