<?php

namespace Webvest\Harvest;

class Project
{
    protected $id;
    protected $name;
    protected $billable;
    protected $code;
    protected $tasks;
    protected $client;
    protected $clientId;
    protected $clientCurrency;
    protected $clientCurrencySymbol;

    public function __construct(array $data)
    {
        $this->id                   = $data['id'] ?? 0;
        $this->name                 = $data['name'] ?? '';
        $this->billable             = $data['billable'] ?? false;
        $this->code                 = $data['code'] ?? '';
        $this->tasks                = array_map(
            function ($data): Task {
                return new Task($data);
            },
            $data['tasks'] ?? []
        );
        $this->client               = $data['client'] ?? '';
        $this->clientId             = $data['client_id'] ?? 0;
        $this->clientCurrency       = $data['client_currency'] ?? '';
        $this->clientCurrencySymbol = $data['client_currency_symbol'] ?? '';
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

    public function getCode(): string
    {
        return $this->code;
    }

    public function getTasks(): array
    {
        return $this->tasks;
    }

    public function getClient(): string
    {
        return $this->client;
    }

    public function getClientId(): int
    {
        return $this->clientId;
    }

    public function getClientCurrency(): string
    {
        return $this->clientCurrency;
    }

    public function getClientCurrencySymbol(): string
    {
        return $this->clientCurrencySymbol;
    }
}
