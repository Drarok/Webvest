<?php

namespace Harvest;

class Task
{
    protected $id;
    protected $name;
    protected $billable;

    public function __construct(array $data)
    {
        $this->id = $data['id'] ?? 0;
        $this->name = $data['name'] ?? '';
        $this->billable = $data['billable'] ?? false;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getBillable(): bool
    {
        return $this->billable;
    }
}
