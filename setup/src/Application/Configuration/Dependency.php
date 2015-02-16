<?php
namespace Application\Configuration;

/**
 * dependency class for shortcuts and class definitions
 *
 * @package Application\Configuration
 * @version 1.0
 * @author York Framework
 */
class Dependency extends \York\Dependency\Manager
{

    /**
     * @return \Application\Foo\Bar
     */
    public static function getADependency()
    {
        return \York\Dependency\Manager::get('aDependency');
    }
}
