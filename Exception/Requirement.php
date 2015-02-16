<?php
namespace York\Exception;

/**
 * exception for having errors on requirement checker
 *
 * @package York\Exception
 * @version $version$
 * @author wolxXx
 */
class Requirement extends \York\Exception\General
{
    /**
     * @var string[]
     */
    protected $messages;

    /**
     * @param string[] $messages
     */
    public function __construct(array $messages)
    {
        $this->messages = $messages;
    }
}
