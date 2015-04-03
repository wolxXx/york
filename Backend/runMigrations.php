<?php
require_once(__DIR__.'/Bootstrap.php');
$script = new \York\Backend\Script\Migrator();
$script
    ->warningOutput('this link is deprecated! please update your link to migrations.php')
    ->newLine(2);
