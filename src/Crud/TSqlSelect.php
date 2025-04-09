<?php

declare(strict_types=1);

namespace Sbintech\Transaction\Crud;

final class TSqlSelect extends TSqlInstruction
{

    private $order = "";
    private $limit = "";

    public function getPreparedInstruction(): string
    {

        $this->sql = "SELECT {$this->column->dump()} FROM {$this->entity}";

        if ($this->criteria) {
            foreach ($this->criteria->getValues() as $key => $value) {
                $this->setRowData(column: $key, value: $value);
            }
            $this->sql .= " WHERE " . $this->criteria->dump();
        }

        if ($this->join) {
            $this->sql .= $this->join->dump();
        }
        $this->sql .= $this->order;
        $this->sql .= $this->limit;
        return $this->sql;
    }

    public function setOrder(string $column, string $type = "DESC")
    {
        $this->order = " ORDER BY {$column} {$type}";
    }

    public function setLimit(int $limit, int $offset = 0)
    {
        if ($offset == 0) {

            $this->limit = " LIMIT {$limit}";
        } else {
            
            $this->limit = " LIMIT {$limit} OFFSET {$offset}";
        }
    }

    public function groupBy(string $column)
    {

    }


    public function execute(\PDO $pdo): array|object
    {
        $stmt = $pdo->prepare(query: $this->getPreparedInstruction());
        $this->bindValues(stmt: $stmt);
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_OBJ);
    }
}