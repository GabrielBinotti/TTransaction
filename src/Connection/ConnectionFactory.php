<?php
declare(strict_types = 1);

namespace Sbintech\Transaction\Connection;

final class ConnectionFactory
{

    public static function create(string $file): \PDO
    {
        $config = "{$file}";

        if (!file_exists(filename: $config)) {
            throw new \Exception(message: "Error: file {$file} not exists!");   
        }

        $config = parse_ini_file(filename: $file, process_sections: true);

        $dsn = match ($config["driver"]) {
            "mysql" => "mysql:host={$config['host']};dbname={$config['dbname']};port={$config['port']}",
            "pgsql" => "pgsql:host={$config['host']};dbname={$config['dbname']};port={$config['port']}",
            default => throw new \Exception(message: "Error: Driver {$config['driver']} not acepted!")
        };

        $pdo = new \PDO(dsn: $dsn, username: $config["user"], password: $config["pass"]);
        $pdo->setAttribute(attribute: \PDO::ATTR_ERRMODE, value: \PDO::ERRMODE_EXCEPTION);
        $pdo->setAttribute(attribute: \PDO::ATTR_DEFAULT_FETCH_MODE, value: \PDO::FETCH_OBJ);
        $pdo->exec(statement: "SET NAMES '" . $config['charset'] . "'");
        return $pdo;

    }
}