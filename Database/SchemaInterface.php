<?php
namespace York\Database;

/**
 * SchemaInterface
 *
 * @package York\Database
 * @version $version$
 * @author wolxXx
 */
interface SchemaInterface
{
    /**
     * @param string $tableName
     *
     * @return boolean
     */
    public function truncate($tableName);

    /**
     * @param string $tableName
     *
     * @return boolean
     */
    public function removeTable($tableName);

    /**
     * @param string    $tableName
     * @param string    $column
     *
     * @return boolean
     */
    public function hasColumn($tableName, $column);

    /**
     * @param array $configuration
     *
     * @return boolean
     */
    public function addColumn($configuration);

    /**
     * @param array $configuration
     *
     * @return boolean
     */
    public function removeColumn($configuration);

    /**
     * @param array $configuration
     *
     * @return boolean
     */
    public function updateColumn($configuration);
}
