<?php
namespace York\Logger;

/**
 * sends the log message as an email
 *
 * @package \York\Logger
 * @version $version$
 * @author wolxXx
 */
class Email extends LoggerAbstract
{
    /**
     * @var \York\Mailer
     */
    protected $mailer;

    /**
     * @return $this
     */
    public static function Factory()
    {
        return new self();
    }

    /**
     * @inheritdoc
     */
    protected function init()
    {
        $this->mailer = \York\Mailer::Factory()
            ->addReceiver(\Application\Configuration\Application::$ADMIN_EMAIL)
            ->setSender(\Application\Configuration\Application::$ADMIN_EMAIL)
            ->setSubject('Log-Message from ' . \Application\Configuration\Application::$APP_NAME);

        return $this;
    }

    /***
     * @inheritdoc
     */
    protected function logAction($message)
    {
        $this->mailer
            ->setText($message)
            ->send();

        return $this;
    }

    /**
     * @inheritdoc
     * @todo implement me!
     */
    public function validate()
    {
        return $this;
    }
}
