<?php
declare(strict_types=1);

namespace Sbintech\Transaction\Crud;

final class TSqlInsert extends TSqlInstruction
{
    protected array $column_values;

    public function getPreparedInstruction(): string
    {
        $columns = array_keys($this->column_values);
        $placeholders = implode(separator: ", ", array: array_map(callback: fn($col): string => ":$col", array: $columns));
        $columns = implode(separator: ", ", array: array_keys($this->column_values));
        $this->sql = "INSERT INTO {$this->entity} ({$columns}) VALUES ({$placeholders})";
        return $this->sql;
    }


    public function execute(\PDO $pdo): int
    {
        $stmt = $pdo->prepare(query: $this->getPreparedInstruction());
        $this->bindValues(stmt: $stmt);
        $stmt->execute();
        return (int) $pdo->lastInsertId();
    }
}