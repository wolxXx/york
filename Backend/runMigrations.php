<?php
require_once(__DIR__.'/Bootstrap.php');
$script = new \York\Backend\Script\Migrator('generate models', '$version$');
echo "warning: this link is deprecated! please update your link to migrations.php".PHP_EOL.PHP_EOL;
