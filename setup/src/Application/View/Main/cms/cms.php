<?php
/**
 * @var \York\View\Manager          $this
 * @var \Application\Model\Content  $entry
 */
$this->partial('layout/titles', $entry->getTitle());
echo nl2br($entry->getText());
