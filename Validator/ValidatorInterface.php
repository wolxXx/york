<?php
namespace York\Validator;
/**
 * validator interface
 *
 * @package \York\Validator
 * @version $version$
 * @author wolxXx
 */
interface ValidatorInterface
{
    /**
     * @param mixed $data
     *
     * @return boolean
     *
     * @throws \York\Exception\Validator
     */
    public function isValid($data);
}
