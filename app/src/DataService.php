<?php

namespace Webvest;

use DateTime;
use PDO;

class DataService
{
    const STATE_STARTED = 'started';
    const STATE_STOPPED = 'stopped';

    private $pdo;

    public function __construct($path)
    {
        $this->pdo = new PDO('sqlite:' . $path, '', '', [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_STRINGIFY_FETCHES  => false,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ]);
        $this->createTables();
    }

    public function addInterruption(int $entryId, string $state)
    {
        $stmt = $this->pdo->prepare(
            'INSERT INTO "interruptions" ("entryId", "time", "state") VALUES (:entryId, :time, :state)'
        );

        $stmt->execute([
            'entryId' => $entryId,
            'time' => (new DateTime())->format('Y-m-d H:i:s'),
            'state' => $state,
        ]);
    }

    public function getInterruptions(int $entryId, string $state = null): array
    {
        $sql = 'SELECT * FROM "interruptions" WHERE "entryId" = :entryId';
        $params = [
            'entryId' => $entryId,
        ];

        if ($state) {
            $sql .= ' AND "state" = :state';
            $params['state'] = $state;
        }

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);

        $result = [];
        while ($row = $stmt->fetch()) {
            $result[] = $row;
        }

        return $result;
    }

    private function createTables()
    {
        $stmt = $this->pdo->prepare('SELECT COUNT(*) AS cnt FROM sqlite_master WHERE type=:type AND name = :name');
        $stmt->execute([
            'type' => 'table',
            'name' => 'interruptions',
        ]);

        $row = $stmt->fetch();
        $exists = intval($row['cnt'] ?? '0') > 0;

        if ($exists) {
            return;
        }

        $this->pdo->query(implode(' ', [
            'CREATE TABLE "interruptions" (',
            '"id" INTEGER PRIMARY KEY AUTOINCREMENT,',
            '"entryId" INTEGER NOT NULL,',
            '"time" TEXT NOT NULL,',
            '"state" TEXT NOT NULL',
            ')',
        ]));

        $this->pdo->query('CREATE INDEX "interruptions:entryId" ON "interruptions" ("entryId")');
    }
}
