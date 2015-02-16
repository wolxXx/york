<?php
namespace York\Backend;

require_once(__DIR__.'/../Autoload/Manager.php');
new \York\Autoload\Manager();

chdir(\York\Helper\Application::getProjectRoot());

class Bootstrap extends \York\Bootstrap{}

new \York\Backend\Bootstrap();
