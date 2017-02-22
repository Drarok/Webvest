<?php

namespace Webvest;

use PDO;

class DataService
{
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

    private function createTables()
    {
        $stmt = $this->pdo->prepare('SELECT COUNT(*) AS cnt FROM sqlite_master WHERE type=:type AND name = :name');
        $stmt->execute([
            'type' => 'table',
            'name' => 'timers',
        ]);

        $row = $stmt->fetch();
        $exists = intval($row['cnt'] ?? '0') > 0;

        if ($exists) {
            return;
        }

        // TODO: Create the schema here.
    }
}
