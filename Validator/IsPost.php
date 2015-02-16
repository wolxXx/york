<?php
namespace York\Validator;

/**
 * validator for checking that the current request contains post data
 *
 * @package \York\Validator
 * @version $version$
 * @author wolxXx
 */
class IsPost implements \York\Validator\ValidatorInterface
{
    /**
     * @inheritdoc
     */
    public function isValid($data)
    {
        if (false === \York\Dependency\Manager::getRequestManager()->isPost()) {
            throw new \York\Exception\Validator('not a post request');
        }

        return true;
    }
}
