<?php
/**
 * @var \York\View\Manager  $this
 * @var string[]            $models
 */
?>

<h1><?= \Application\Configuration\Dependency::getTranslator()->translate('Modelübersicht') ?></h1>
<div class="container_20">
    <?php foreach ($models as $current): ?>
        <div class="grid_3">
            <a href="/admin/listModel/<?= $current->TABLE_NAME ?>"><?= $current->TABLE_NAME ?></a>
        </div>
    <?php endforeach ?>
</div>
