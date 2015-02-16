<?php
/**
 * @var \York\View\Manager  $this
 * @var string              $type
 */
$this->set('title', \Application\Configuration\Dependency::getTranslator()->translate('OooooooOoooops - Es ist ein Fehler aufgetreten'));
$headline = '';
$message = '';
switch ($type) {
    case '404': {
        $headline = \Application\Configuration\Dependency::getTranslator()->translate('Seite nicht gefunden!');
        $message = \Application\Configuration\Dependency::getTranslator()->translate('Die Seite, welche du unter dieser URL zu finden versuchst, ist schlichtweg nicht existent.');
        $message .= '<br />';
        $message .= \Application\Configuration\Dependency::getTranslator()->translate('Bitte überprüfe die URL. Sollte das Problem häufiger auftreten, %s.',
            '<a href="/contact">' . \Application\Configuration\Dependency::getTranslator()->translate('kontaktiere uns bitte') . '</a>');
    }
        break;
    case 'noView':
    case 'no_view': {
        $headline = \Application\Configuration\Dependency::getTranslator()->translate('Keine Ausgabe gefunden');
        $message = \Application\Configuration\Dependency::getTranslator()->translate('Zu dieser Seite existiert keine Ausgabedatei. Es ist ein Programmlogikfehler. %s, damit wir den Fehler beheben können.', '<a href="/contact">' . \Application\Configuration\Dependency::getTranslator()->translate('Kontaktiere uns bitte') . '</a>');
    }
        break;
    case 'app': {
        $headline = \Application\Configuration\Dependency::getTranslator()->translate('Programmlogikfehler');
        $message = \Application\Configuration\Dependency::getTranslator()->translate('Es ist ein Fehler innerhalb der Programmlogik aufgetreten. %s, damit wir den Fehler beheben können.', '<a href="/contact">' . \Application\Configuration\Dependency::getTranslator()->translate('Kontaktiere uns bitte') . '</a>');

    }
        break;
    case 'no_auth': {
        $headline = \Application\Configuration\Dependency::getTranslator()->translate('Authentifizierung benötigt.');
        $message = \Application\Configuration\Dependency::getTranslator()->translate('Das Programm lässt dich nur zu, wenn du angemeldet bist.');
    }
        break;
    case 'pending': {
        $headline = \Application\Configuration\Dependency::getTranslator()->translate('Noch nicht freigeschaltet.');
        $message = \Application\Configuration\Dependency::getTranslator()->translate('Dieses Benutzerkonto wurde noch nicht aktiviert. Das macht ein Admin. Bei erfolgreicher Freischaltung wirst du per Email informiert.');
    }
        break;
    case 'banned': {
        $headline = \Application\Configuration\Dependency::getTranslator()->translate('Dieses Benutzerkonto wurde verbannt.');
        $message = \Application\Configuration\Dependency::getTranslator()->translate('Mit dieser Email-Adresse kannst du dich nicht mehr anmelden, da du verbannt wurdest.');
    }
        break;
    case '403': {
        $headline = \Application\Configuration\Dependency::getTranslator()->translate('Zutritt verweigert.');
        $message = \Application\Configuration\Dependency::getTranslator()->translate('Der Zugriff auf diese Seite wird verwehrt.');
    }
        break;
    default: {
        $headline = \Application\Configuration\Dependency::getTranslator()->translate('Es ist ein Fehler aufgetreten.');
        $message = \Application\Configuration\Dependency::getTranslator()->translate('%s, damit wir den Fehler beheben können.', '<a href="/contact">' . \Application\Configuration\Dependency::getTranslator()->translate('Kontaktiere uns bitte') . '</a>');
    }
        break;
}
$this->set('headline', $headline);
?>
<div class="box75">
    <div class="header">
        <?= $this->get('headline') ?>
    </div>
    <div class="boxcontent">
        <?= $message ?>
        <?php if (true === \York\Dependency\Manager::getApplicationConfiguration()->getSafely('debug')): ?>
            <?php if (null !== $last_error): ?>
                <h3><?= $last_error->getMessage() ?></h3>
                <?php \York\Helper\Application::debug($last_error) ?>
            <?php else: ?>
                <pre><?= \Application\Configuration\Dependency::getTranslator()->translate('-Keine Fehlerinformationen verfügbar-') ?></pre>
            <?php endif ?>
        <?php endif ?>
    </div>
</div>
