<?php
require_once('nma.php');

$nma = new NMA();

$nma->addApiKey('xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx');
$nma->addApiKey('yyyyyyyyyyyyyyyyyyyyyyyyyyyyyyyyyyyyyyyyyyyyyyyy');
$nma->setApplication('My Application');
$nma->setEvent('New Event');
$nma->setDescription('Notification Text');
$nma->setPriority(-1);

$nma->send();
?>
