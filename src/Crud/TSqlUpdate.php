<?php
declare(strict_types=1);

namespace Sbintech\Transaction\Crud;

final class TSqlUpdate extends TSqlInstruction
{

    protected array $column_values;


    public function getPreparedInstruction(string $driver = ""): string
    {
        $set = [];
        foreach ($this->column_values as $col => $val) {
            $set[] = "{$col} = :{$col}";
        }
        $set_string = implode(separator: ', ', array: $set);
        $this->sql = "UPDATE {$this->entity} SET {$set_string}";

        if ($this->criteria) {
            foreach ($this->criteria->getValues() as $key => $value) {
                $this->setRowData(column: $key, value: $value);
            }
            $this->sql .= " WHERE " . $this->criteria->dump();
        }
        return $this->sql;
    }


    public function execute(\PDO $pdo): int
    {
        $stmt = $pdo->prepare(query: $this->getPreparedInstruction());
        $this->bindValues(stmt: $stmt);
        $stmt->execute();
        return (int) $stmt->rowCount();
    }
}