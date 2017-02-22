<?php

namespace Webvest\Controller;

use DateTime;

class IndexController extends AbstractController
{
    public function render(): string
    {
        $data = $this->getData();
        return $this->app['viewService']->render('index.html.twig', $data);
    }

    protected function getData(): array
    {
        // If viewing before 07:00, presume it's a little after midnight and show
        // the previous day, which is more likely to have some data.
        $date = new DateTime();
        if ($date->format('H:i:s') < '07:00:00') {
            $date->modify('-1 day');
        }
        $daily = $this->app['client']->getDaily($date);

        $mostRecentEntryId = false;
        $mostRecentDate = null;
        foreach ($daily->getDayEntries() as $entry) {
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

        return [
            'daily'             => $daily,
            'mostRecentEntryId' => $mostRecentEntryId,
        ];
    }
}
