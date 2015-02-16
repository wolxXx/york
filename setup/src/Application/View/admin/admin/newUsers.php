<?
/**
 * @var \Application\Model\User[]   $users
 * @var \York\View\Manager          $this
 */

?>
<h1><?= \Application\Configuration\Dependency::getTranslator()->translate('Neue Benutzer') ?></h1>
<?php if (true === empty($users)): ?>
    <pre><?= \Application\Configuration\Dependency::getTranslator()->translate(' - keine neuen Benutzer - ') ?></pre>
<?php endif ?>
<?php foreach ($users as $user): ?>
    <div style="border: solid 1px #000; border-radius: 5px; float: left; margin: 5px; padding: 10px;">
        <dl>
            <dt><?= \Application\Configuration\Dependency::getTranslator()->translate('Nick') ?></dt>
            <dd><?= $user->getNick() ?></dd>

            <dt><?= \Application\Configuration\Dependency::getTranslator()->translate('Email') ?></dt>
            <dd><?= $user->getEmail() ?></dd>

            <dt><?= \Application\Configuration\Dependency::getTranslator()->translate('Registriert am') ?></dt>
            <dd><?= $user->getCreated()->format(\Application\Configuration\Application::dateFormat) ?></dd>
        </dl>
        <a href="/admin/setUserStatus/<?= $user->getId() ?>/<?= \York\Configuration::$USER_STATUS_BANNED ?>"><?= \Application\Configuration\Dependency::getTranslator()->translate('verbannen') ?></a>
        |
        <a href="/admin/setUserStatus/<?= $user->getId() ?>/<?= \York\Configuration::$USER_STATUS_ACTIVATED ?>"><?= \Application\Configuration\Dependency::getTranslator()->translate('aktivieren') ?></a>
    </div>
<?php endforeach ?>
