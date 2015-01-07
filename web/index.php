<?php

error_reporting(E_ALL);

$app = require_once __DIR__ . '/../app/bootstrap.php';

$app['exceptionHandler']->install();

$data = $app['client']->getDaily();

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
