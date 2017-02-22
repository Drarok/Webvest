<?php

namespace Webvest\Harvest;

use DateTime;

class Daily
{
    protected $forDay;
    protected $dayEntries;
    protected $projects;

    public function __construct(array $data)
    {
        $this->forDay     = $data['for_day'] ?? '';
        $this->dayEntries = array_map(
            function (array $data): Entry {
                return new Entry($data);
            },
            $data['day_entries'] ?? []
        );
        $this->projects   = array_map(
            function ($data): Project {
                return new Project($data);
            },
            $data['projects'] ?? []
        );
    }

    public function getForDay(): string
    {
        return $this->forDay;
    }

    public function getDayEntries(): array
    {
        return $this->dayEntries;
    }

    public function getProjects(): array
    {
        return $this->projects;
    }
}
