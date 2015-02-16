<?php
namespace York\Logger;

/**
 * level class container
 *
 * @package \York\Logger
 * @version $version$
 * @author wolxXx
 */
class Level
{
    /**
     * logger level all
     *
     * @var string
     */
    const ALL = 'LOG_LEVEL_ALL';

    /**
     * logger level warn
     *
     * @var string
     */
    const WARN = 'LOG_LEVEL_WARN';

    /**
     * logger level debug
     *
     * @var string
     */
    const DEBUG = 'LOG_LEVEL_DEBUG';

    /**
     * logger level error
     *
     * @var string
     */
    const ERROR = 'LOG_LEVEL_ERROR';

    /**
     * logger level notice
     *
     * @var string
     */
    const NOTICE = 'LOG_LEVEL_NOTICE';

    /**
     * logger level database error
     *
     * @var string
     */
    const DATABASE_ERROR = 'LOG_LEVEL_DATABASE_ERROR';

    /**
     * logger level database debug
     *
     * @var string
     */
    const DATABASE_DEBUG = 'LOG_LEVEL_DATABASE_DEBUG';

    /**
     * logger level log post
     *
     * @var string
     */
    const LOG_POST = 'LOG_LEVEL_LOG_POST';

    /**
     * logger level email send
     *
     * @var string
     */
    const EMAIL = 'LOG_LEVEL_EMAIL';

    /**
     * logger level email sending failed
     *
     * @var string
     */
    const EMAIL_FAILED = 'LOG_LEVEL_EMAIL_FAILED';

    /**
     * logger level york framework debug
     *
     * @var string
     */
    const YORK_DEBUG = 'LOG_LEVEL_YORK_DEBUG';

    /**
     * logger level application debug
     *
     * @var string
     */
    const APPLICATION_DEBUG = 'LOG_LEVEL_APPLICATION_DEBUG';

    /**
     * logger level api response
     *
     * @var string
     */
    const API_RESPONSE = 'LOG_LEVEL_API_RESPONSE';

    /**
     * logger level console run
     *
     * @var string
     */
    const CONSOLE_RUN = 'LOG_LEVEL_CONSOLE_RUN';

    /**
     * logger level application defined
     *
     * @var string
     */
    const APPLICATION_DEFINED = 'LOG_LEVEL_APPLICATION_DEFINED';
}
