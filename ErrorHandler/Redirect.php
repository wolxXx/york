<?php
namespace York\ErrorHandler;
/**
 * redirect exception handler
 *
 * @package York\ErrorHandler
 * @version $version$
 * @author wolxXx
 */
class Redirect extends ErrorHandlerAbstract
{
    /**
     * @return \York\Exception\Redirect
     */
    public function getException()
    {
        return parent::getException();
    }

    /**
     * @inheritdoc
     */
    public function handle()
    {
        if (null !== $this->getException()->target) {
            $redirect = new \York\Redirect($this->getException()->target);
            $redirect->redirect();

            die();
        }

        $redirect = \York\Dependency\Manager::getBootstrap()->controller->getRegisteredRedirect();

        if (null === $redirect) {
            $redirect = \Application\Configuration\Dependency::getApplicationConfiguration()->getSafely('requestedRedirect', null);

            if (null === $redirect) {
                $redirect = new \York\Redirect('/');
            }
        }

        $redirect->redirect();

        return $this;
    }
}
