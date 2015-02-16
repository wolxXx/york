<?php

/**
 * launch the application by including the York file.
 * it will do the rest of the fest
 */
chdir(__DIR__.'/../');
require_once 'vendor/wolxxx/york/York.php';
$york = new  \York\York();
$york->run();
