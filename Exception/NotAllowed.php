<?php
namespace York\Exception;

/**
 * exception for not allowed actions
 *
 * @package York\Exception
 * @version $version$
 * @author wolxXx
 */
class NotAllowed extends \York\Exception\Auth
{
    /**
     * @inheritdoc
     */
    public function __construct()
    {
        \York\Dependency\Manager::getSplashManager()->addText(\York\Dependency\Manager::getTranslator()->translate('Diese Seite ist für dich nicht bestimmt!'));

        \York\Redirect::Factory()
            ->setUrl('/error/403')
            ->redirect();
    }
}
