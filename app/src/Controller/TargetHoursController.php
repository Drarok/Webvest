<?php

namespace Controller;

use DateTime;

class TargetHoursController extends AbstractController
{
    public function render(): string
    {
        $data = $this->getData();
        return $this->app['viewService']->render('target-hours.html.twig', $data);
    }

    protected function getData(): array
    {
        $workingDays = $this->getWorkingDays();

        $numberOfWorkingDays = count(array_filter($workingDays));

        return [
            'workingDays'          => $workingDays,
            'numberOfWorkingDays'  => $numberOfWorkingDays,
            'targetPerDay'         => sprintf('%.3f', 7.5 * (2 / 3)),
            'loggedHours'          => $this->getLoggedHours(),
        ];
    }

    /**
     * Get an array; the key is the day number and value represents if it's a
     * working day or not as a bool.
     *
     * @return array
     */
    protected function getWorkingDays(): array
    {
        $m = date('m');
        $y = date('Y');

        $holidays = $this->app['config']->holidays;

        $workingDays = [];

        $weekdays = 0;
        $lastDay = date('t', mktime(0, 0, 0, $m, 1, $y));
        for ($d = 1; $d <= $lastDay; ++$d) {
            $currentDay = DateTime::createFromFormat('Y-m-d', sprintf('%d-%d-%d', $y, $m, $d));
            $wd = $currentDay->format('w');

            // Weekends are never working days.
            if ($wd == 0 || $wd == 6) {
                $workingDays[] = false;
                continue;
            }

            $currentDayString = $currentDay->format('Y-m-d');
            if (in_array($currentDayString, $holidays)) {
                $workingDays[] = false;
                continue;
            }

            $workingDays[] = true;
        }

        return $workingDays;
    }

    protected function getLoggedHours()
    {
        $fromDate = DateTime::createFromFormat('Ymd', date('Ym') . '01');
        $toDate = new DateTime();

        $dailyHours = [];
        for ($i = 1; $i <= (int)date('j'); ++$i) {
            $dailyHours[$i] = 0;
        }

        $entries = $this->app['client']->getEntriesForUser(
            $this->app['config']->harvest->userId,
            DateTime::createFromFormat('Ymd', date('Ym') . '01'),
            new DateTime(),
            ['billable' => true]
        );

        // Calculate the hours for each day.
        foreach ($entries as $entry) {
            // Ignore overtime tasks.
            if (strtolower(substr($entry->getNotes(), 0, 9)) == 'overtime:') {
                continue;
            }

            $dailyHours[(int)$entry->getSpentAt()->format('j')] += $entry->getHours();
        }

        // Return the data in a cumulative array.
        $totalHours = 0;
        $loggedHours = [];
        foreach ($dailyHours as $h) {
            $totalHours += $h;
            $loggedHours[] = $totalHours;
        }

        return $loggedHours;
    }
}
