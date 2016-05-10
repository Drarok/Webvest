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

        return $weekdays + 20;
    }

    protected function getLoggedHours()
    {
        $d = (int)date('j');
        $m = (int)date('m');
        $y = (int)date('Y');

        $loggedHours = [];
        $totalHours = 0;
        for ($i = 1; $i <= $d; ++$i) {
            $date = DateTime::createFromFormat('Y-m-d H:i:s', sprintf('%d-%d-%d 00:00:00', $y, $m, $i));
            foreach ($this->app['client']->getDaily($date) as $entry) {
                if (strtolower(substr($entry->getNotes(), 0, 9)) == 'overtime:') {
                    continue;
                }

                $totalHours += $entry->getHours();
            }
            $loggedHours[] = $totalHours;
        }

        return $loggedHours;
    }
}
