<?php
namespace York\Hook;

/**
 * hook item
 *
 * @package York\Hook
 * @version $version$
 * @author wolxXx
 */
class Item
{
    /**
     * @var string
     */
    protected $event;

    /**
     * @var callable
     */
    protected $call;

    /**
     * @var integer
     */
    protected $priority;

    /**
     * @param string    $event
     * @param callable  $call
     * @param integer   $priority
     */
    public function __construct($event, $call, $priority = \York\Hook\Priority::NONE)
    {
        $this
            ->setEvent($event)
            ->setCall($call)
            ->setPriority($priority)
        ;
    }

    /**
     * factory function
     *
     * @param string    $event
     * @param callable  $call
     * @param integer   $priority
     *
     * @return \York\Hook\Item
     */
    public static function Factory($event, $call, $priority = \York\Hook\Priority::NONE)
    {
        return new self($event, $call, $priority);
    }

    /**
     * setter for the priority
     *
     * @param integer $priority
     *
     * @return $this
     */
    public function setPriority($priority)
    {
        $this->priority = $priority;

        return $this;
    }

    /**
     * getter for the priority
     *
     * @return integer
     */
    public function getPriority()
    {
        return $this->priority;
    }

    /**
     * setter for the event
     *
     * @param string $event
     *
     * @return $this
     */
    public function setEvent($event)
    {
        $this->event = $event;

        return $this;
    }

    /**
     * getter for the event
     *
     * @return string
     */
    public function getEvent()
    {
        return $this->event;
    }

    /**
     * setter for the call
     *
     * @param callable $call
     *
     * @return $this
     */
    public function setCall($call)
    {
        $this->call = $call;

        return $this;
    }

    /**
     * run the callable $call
     *
     * @return $this
     */
    public function run()
    {
        call_user_func($this->call);

        return $this;
    }
}
