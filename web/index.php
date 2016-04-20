<?php

error_reporting(E_ALL);

$app = require_once __DIR__ . '/../app/bootstrap.php';

$app['exceptionHandler']->install();

// If viewing before 07:00, presume it's a little after midnight and show
// the previous day, which is more likely to have some data.
$date = new DateTime();
if ($date->format('H:i:s') < '07:00:00') {
    $date->modify('-1 day');
}
$data = $app['client']->getDaily($date);

$mostRecentEntryId = false;
$mostRecentDate = null;
foreach ($data as $entry) {
    if ($mostRecentDate === null) {
        $mostRecentDate = $entry->getUpdatedAt();
        $mostRecentEntryId = $entry->getId();
    } else {
        $entryDate = $entry->getUpdatedAt();
        if ($entryDate > $mostRecentDate) {
            $mostRecentDate = $entryDate;
            $mostRecentEntryId = $entry->getId();
        }
    }
}

echo $app['viewService']->render(
    'index.html.twig',
    array(
        'daily'             => $data,
        'mostRecentEntryId' => $mostRecentEntryId,
    )
);
