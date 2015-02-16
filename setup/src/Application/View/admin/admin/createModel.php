<?php
/**
 * @var \York\View\Manager  $this
 * @var string              $table
 * @var array               $columns
 */
require_once __DIR__ . '/columnInput.php';
?>

<h1>
    <?php echo \Application\Configuration\Dependency::getTranslator()->translate('Neuen Eintrag für Model "%s" erstellen', $table) ?>
</h1>

<button onclick="document.location = '/admin/listModel/<?php echo $table ?>';">
    zurück
</button>

<form action="" method="post">
    <table>
        <tr>
            <th>
                <?php echo \Application\Configuration\Dependency::getTranslator()->translate('Feld') ?>
            </th>
            <th>
                <?php echo \Application\Configuration\Dependency::getTranslator()->translate('Wert') ?>
            </th>
            <th>

            </th>
        </tr>
        <?php foreach ($columns as $current): ?>
            <?php
            $input = getInput($current, null);
            if (null === $input) {
                continue;
            }
            ?>
            <tr>
                <td>
                    <nobr>
                        <?php
                            if (null === $input->getLabel()) {
                                $input->addLabel(sprintf('%s (%s)', $current->COLUMN_NAME, $current->DATA_TYPE));
                            }
                            $input->getLabel()->display();
                            $input->clearLabel();
                        ?>
                    </nobr>
                </td>
                <td>
                    <?php $input->display() ?>
                </td>
            </tr>
        <?php endforeach ?>
        <tr>
            <td>
                <input type="reset"
                       value="<?php echo \Application\Configuration\Dependency::getTranslator()->translate('zurücksetzen') ?>">
            </td>
            <td>
                <input type="submit"
                       value="<?php echo \Application\Configuration\Dependency::getTranslator()->translate('speichern') ?>">
            </td>
        </tr>
    </table>
</form>
