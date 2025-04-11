<?php

declare(strict_types=1);

namespace Sbintech\Transaction\Crud;

final class TSqlSelect extends TSqlInstruction
{

    private $order = "";
    private $limit = "";

    public function getPreparedInstruction(string $driver = ""): string
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

    public function setLimit(int $limit, int $offset = 0, \PDO $pdo)
    {
        $driver = $this->getDriver(pdo: $pdo);

        if ($offset == 0) {

            $this->limit = " LIMIT {$limit}";
        } else {
            if ($driver == "pgsql") {
                $this->limit = " OFFSET {$offset} LIMIT {$limit}";
            }else if ($driver == "mysql") {
                $this->limit = " LIMIT {$limit},{$offset}";
            }
        }
    }

    public function groupBy(string $column)
    {

    }


    public function execute(\PDO $pdo): array|object
    {
        $driver = $this->getDriver(pdo: $pdo);
        $stmt = $pdo->prepare(query: $this->getPreparedInstruction(driver: $driver));
        $this->bindValues(stmt: $stmt);
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_OBJ);
    }


    private function getDriver(\PDO $pdo)
    {
        return $pdo->getAttribute(attribute: \PDO::ATTR_DRIVER_NAME);
    }
}