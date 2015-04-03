<?php
namespace York\Logger;

/**
 * database logger
 *
 * @package \York\Logger
 * @version $version$
 * @author wolxXx
 */
class Database extends LoggerAbstract
{
    /**
     * name of the table where the logs should be put to
     *
     * @var string
     */
    protected $table;

    /**
     * @return $this
     */
    public static function Factory()
    {
        return new self();
    }

    /**
     * setter for the table name
     *
     * @param string $table
     *
     * @return $this
     */
    public function setTable($table)
    {
        $this->table = $table;

        return $this;
    }

    /**
     * @return string
     */
    public function getTable()
    {
        return $this->table;
    }

    /**
     * @inheritdoc
     */
    protected function logAction($message)
    {
        \York\Database\Accessor\Factory::getSaveObject($this->table)
            ->set('created', \York\Helper\Date::getDate())
            ->set('message', $message)
            ->save();

        return $this;
    }

    /**
     * @inheritdoc
     *
     * @todo implement me!
     * @todo check if database is configured and table exists
     * @todo create table if not exists?! dunno...
     */
    public function validate()
    {
        return $this;
    }
}
