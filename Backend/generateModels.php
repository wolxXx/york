<?php
require_once(__DIR__.'/Bootstrap.php');

$script = new \York\Backend\Script\ModelGenerator();
$script
    ->warningOutput('this link is deprecated! please update your link to models.php')
    ->newLine(2);
