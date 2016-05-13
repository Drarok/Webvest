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
        $days = $this->getWorkingDays();

        return [
            'workingDays'  => $days,
            'targetPerDay' => sprintf('%.3f', 100 / $days),
            'loggedHours'  => $this->getLoggedHours(),
        ];
    }

    /**
     * Logical analysis says the first 28 days _must_ have 20 working days in,
     * so we only loop over 29 and up, then add 20 at the end.
     *
     * @return int
     */
    protected function getWorkingDays(): int
    {
        $m = (int)date('n');
        $y = (int)date('Y');

        $weekdays = 0;
        $lastDay = date('t', mktime(0, 0, 0, $m, 1, $y));
        for ($d = 29; $d <= $lastDay; ++$d) {
            $wd = date('w', mktime(0, 0, 0, $m, $d, $y));
            if ($wd > 0 && $wd < 6) {
                $weekdays++;
            }
        }

        // Reduce by number of holidays that fall within this month.
        $thisMonth = date('Y-m');
        $holidays = $this->app['config']->holidays;
        foreach ($holidays as $holiday) {
            $holiday = DateTime::createFromFormat('Y-m-d', $holiday);
            if ($holiday->format('Y-m') === $thisMonth) {
                --$weekdays;
            }
        }

        return $weekdays + 20;
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
