<?php
namespace York\Database\Blueprint;

/**
 * Interface BlueprintInterface
 *
 * @package \York\Database\Blueprint
 * @version $version$
 * @author wolxXx
 */
interface ItemInterface
{
    /**
     * init your references
     *
     * @return $this
     */
    public function initReferencing();

    /**
     * validate yourself
     *
     * @return $this
     */
    public function validate();
}
