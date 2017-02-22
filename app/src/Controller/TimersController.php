<?php

namespace Webvest\Controller;

use DateTime;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class TimersController extends AbstractController
{
    public function indexAction(): Response
    {
        return $this->render('timers/index.html.twig', $this->getData());
    }

    public function toggleAction(Request $request): Response
    {
        $date = $request->query->get('date', '');
        $id = intval($request->query->get('id', '0'));

        $date = DateTime::createFromFormat('Y-m-d', $date);
        if (!$date || !$id) {
            throw new InvalidArgumentException('Invalid request');
        }

        $client = $this->app['client'];
        $client->toggleTimer($date, $id);
        return new RedirectResponse('/');
    }

    private function getCurrentDate(): DateTime
    {
        // If viewing before 07:00, presume it's a little after midnight and show
        // the previous day, which is more likely to have some data.
        $date = new DateTime();
        if ($date->format('H:i:s') < '07:00:00') {
            $date->modify('-1 day');
        }

        return $date;
    }

    private function getData(): array
    {
        $daily = $this->app['client']->getDaily($this->getCurrentDate());

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
