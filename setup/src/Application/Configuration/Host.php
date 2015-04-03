<?php
namespace Application\Configuration;

/**
 * host configuration
 *
 * @package Application\Configuration
 * @version 1.0
 * @author  York Framework
 *
 * @codeCoverageIgnore
 */
class Host extends Application
{
    /**
     * @inheritdoc
     * @codeCoverageIgnore
     */
    public function configureHost()
    {
        \Application\Configuration\Dependency::getDatabaseConfiguration()
            ->set('db_host', 'localhost')
            ->set('db_user', 'root')
            ->set('db_pass', 'password')
            ->set('db_schema', 'database');
        
        Dependency::getApplicationConfiguration()
            ->set('app_url', 'my-page.org.local')
            ->set('foo', 'bar');
        
        return $this;
    }
}
