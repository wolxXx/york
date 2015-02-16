<?
/** @var $users \Application\Model\User[] */
$statuses = array(
	\York\Configuration::$USER_STATUS_PENDING => \Application\Configuration\Dependency::getTranslator()->translate('wartend'),
	\York\Configuration::$USER_STATUS_ACTIVATED => \Application\Configuration\Dependency::getTranslator()->translate('aktiviert'),
	\York\Configuration::$USER_STATUS_BANNED => \Application\Configuration\Dependency::getTranslator()->translate('verbannt')
);
$types = array(
	\York\Configuration::$USER_TYPE_USUAL => \Application\Configuration\Dependency::getTranslator()->translate('Normaler Benutzer'),
	\York\Configuration::$USER_TYPE_EDITOR => \Application\Configuration\Dependency::getTranslator()->translate('Redakteur'),
	\York\Configuration::$USER_TYPE_ADMIN => \Application\Configuration\Dependency::getTranslator()->translate('Administrator')
);
?>

<h1><?= \Application\Configuration\Dependency::getTranslator()->translate('Alle Benutzer') ?></h1>

<?php foreach($users as $user): ?>
	<div class="userBox">
		<h3><?= $user->getNick() ?></h3>
		<dl>
			<dt><?= \Application\Configuration\Dependency::getTranslator()->translate('Zuletzt eingeloggt am') ?></dt>
			<dd><?= null === $user->getLastlog()? \Application\Configuration\Dependency::getTranslator()->translate('noch nie') : $user->getLastlog()->format(\Application\Configuration\Application::dateFormat) ?></dd>

			<dt><?= \Application\Configuration\Dependency::getTranslator()->translate('Registriert am') ?></dt>
			<dd><?= $user->getCreated()->format(\Application\Configuration\Application::dateFormat) ?></dd>
		</dl>
		<hr />
		<ul>
			<?php foreach($statuses as $key => $value): ?>
				<li>
					<a class="<?= $key == $user->getStatus()? 'active' : '' ?>" href="/admin/setUserStatus/<?= $user->getId() ?>/<?= $key ?>"><?= $value ?></a>
				</li>
			<?php endforeach ?>
		</ul>
		<hr />
		<ul>
			<?php foreach($types as $key => $value): ?>
				<li>
					<a class="<?= $key == $user->getType()? 'active' : '' ?>" href="/admin/setUserType/<?= $user->getId() ?>/<?= $key ?>"><?= $value ?></a>
				</li>
			<?php endforeach ?>
		</ul>

	</div>
<?php endforeach ?>
