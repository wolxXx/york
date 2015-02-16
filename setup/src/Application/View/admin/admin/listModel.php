<?php
/**
 * @var \York\View\Manager $this
 * @var array $items
 * @var array $columns
 * @var string $table
 * @var \York\View\Paginator $paginator
 */
?>

<h1><?= \Application\Configuration\Dependency::getTranslator()->translate('Models für Tabelle "%s"', $table) ?></h1>

<?php $this->partial('layout/paginate', $paginator) ?>

<button
    onclick="document.location = '/admin/createModel/<?= $table ?>';"><?= \Application\Configuration\Dependency::getTranslator()->translate('Erstellen') ?></button>
|
<button class="deletePrompter"
        onclick="document.location = '/admin/clearTable/<?= $table ?>';"><?= \Application\Configuration\Dependency::getTranslator()->translate('Alle löschen') ?></button>

<?php if (true === empty($items)): ?>
    <pre> - <?= \Application\Configuration\Dependency::getTranslator()->translate('Keine Einträge') ?> - </pre>
    <?php return ?>
<?php endif ?>

<table>
    <tr>
        <?php foreach ($columns as $current): ?>
            <th><?= $current->COLUMN_NAME ?></th>
        <?php endforeach ?>
        <th>
            <?= \Application\Configuration\Dependency::getTranslator()->translate('Tools') ?>
        </th>
    </tr>
    <?php foreach ($items as $current): ?>
        <tr>
            <?php foreach (get_object_vars($current) as $item): ?>
                <td>
                    <?= $item ?>
                </td>
            <?php endforeach ?>
            <td>
                <a class="deletePrompter"
                   href="/admin/deleteModel/<?= $table ?>/<?= $current->id ?>"><?= \Application\Configuration\Dependency::getTranslator()->translate('löschen') ?></a><br/>
                <a href="/admin/editModel/<?= $table ?>/<?= $current->id ?>"><?= \Application\Configuration\Dependency::getTranslator()->translate('bearbeiten') ?></a><br/>
            </td>
        </tr>
    <?php endforeach ?>
</table>

<?php $this->partial('layout/paginate', $paginator) ?>
