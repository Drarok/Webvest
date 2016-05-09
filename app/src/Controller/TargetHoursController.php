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
}
