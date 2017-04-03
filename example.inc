<?php
//This example is useful for integrating with other scripts
require_once('inc/nma.inc');

$nma = new NMA();

$nma->addApiKey('xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx');
$nma->addApiKey('yyyyyyyyyyyyyyyyyyyyyyyyyyyyyyyyyyyyyyyyyyyyyyyy');
$nma->setApplication('My Application');
$nma->setEvent('New Event');
$nma->setDescription('Notification Text');
$nma->setPriority(-1);

$nma->send();
?>
