<?php
/**
 * @var \York\View\Manager  $this
 * @var integer             $id
 * @var array               $columns
 * @var stdClass            $item
 */
require_once __DIR__ . '/columnInput.php';
?>

<h1>
	<?= \Application\Configuration\Dependency::getTranslator()->translate('Eintrag für Model "%s" mit ID %s bearbeiten', $table, $id) ?>
</h1>

<button onclick="document.location = '/admin/listModel/<?= $table ?>';">
	<?= \Application\Configuration\Dependency::getTranslator()->translate('zurück') ?>
</button>

<form action="" method="post">
	<table>
		<tr>
			<th>
				<?= \Application\Configuration\Dependency::getTranslator()->translate('Feld') ?>
			</th>
			<th>
				<?= \Application\Configuration\Dependency::getTranslator()->translate('Wert') ?>
			</th>
			<th>

			</th>
		</tr>
		<?php foreach($columns as $current): ?>
			<?
				$input = getInput($current, $item->{$current->COLUMN_NAME});
				if(null === $input){
					continue;
				}
			?>
			<tr>
				<td>
					<nobr>
						<?
							if(null === $input->getLabel()){
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
				<input type="reset" value="<?= \Application\Configuration\Dependency::getTranslator()->translate('zurücksetzen') ?>">
			</td>
			<td>
				<input type="submit" value="<?= \Application\Configuration\Dependency::getTranslator()->translate('speichern') ?>">
			</td>
		</tr>
	</table>
</form>
