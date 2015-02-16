<?php
/**
 * @var \York\View\Manager  $this
 * @var string              $content
 */
\Application\Configuration\Dependency::getAssetManager()->addCssFile('/css/admin.css');
?>
<!DOCTYPE html>
<head>
    <title><?= \Application\Configuration\Dependency::getTranslator()->translate('Admin-Page') ?></title>
    <meta http-equiv="content-type" content="text/html; charset=utf-8">
</head>
<body>
<div id="container">
    <div id="menu">
        <?php $this->partial('layout/menu') ?>
    </div>
    <div id="content">
        <?= $content ?>
    </div>
</div>
<? \Application\Configuration\Dependency::getAssetManager()->display(); ?>
</body>
</html>
