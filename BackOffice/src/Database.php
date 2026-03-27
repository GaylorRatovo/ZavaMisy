<?php

namespace ZavaMisy\BackOffice;

use PDO;
use PDOException;

class Database
{
    public static function getConnection(): PDO
    {
        $driver = getenv('DB_DRIVER') ?: 'pgsql';
        $host = getenv('DB_HOST') ?: 'db';
        $port = getenv('DB_PORT') ?: '5432';
        $db   = getenv('DB_NAME') ?: 'zavamisy';
        $user = getenv('DB_USER') ?: 'zava_user';
        $pass = getenv('DB_PASSWORD') ?: 'secret';

        $dsn = sprintf('%s:host=%s;port=%s;dbname=%s', $driver, $host, $port, $db);

        try {
            $pdo = new PDO($dsn, $user, $pass, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            ]);
        } catch (PDOException $e) {
            // En prod, on éviterait d'exposer le message exact
            throw $e;
        }

        return $pdo;
    }
}
