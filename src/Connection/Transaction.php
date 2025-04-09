<?php
declare(strict_types = 1);

namespace Sbintech\Transaction\Connection;


final class Transaction
{
    private static array $stack = [];
    private static array $instances = [];

    public static function open(string $file): void
    {
        if (!isset(self::$instances[$file])) {
            self::$instances[$file] = ConnectionFactory::create(file: $file);
        }
        self::$stack[] = $file;

        $conn = self::$instances[$file];
        if ($conn && !$conn->inTransaction()) {
            $conn->beginTransaction();
        }
    }

    public static function get(): ?\PDO
    {
        if (!empty(self::$stack)) {
            $current = end(array: self::$stack);
            return self::$instances[$current] ?? null;
        }
        return null;
    }

    public static function close(): void
    {
        if (!empty(self::$stack)) {
            $last = array_pop(array: self::$stack);

            if (!in_array(needle: $last, haystack: self::$stack)) {

                $conn = self::$instances[$last] ?? null;
                if ($conn instanceof \PDO && $conn->inTransaction()) {
                    $conn->commit();
                }

                self::$instances[$last] = null;
                unset(self::$instances[$last]);
            }
        }
    }

    public static function rollback(): void
    {
        while (!empty(self::$stack)) {
            $last = array_pop(array: self::$stack);
    
            $conn = self::$instances[$last] ?? null;
            if ($conn instanceof \PDO && $conn->inTransaction()) {
                $conn->rollBack();
            }
    
            self::$instances[$last] = null;
            unset(self::$instances[$last]);
        }
    }
}