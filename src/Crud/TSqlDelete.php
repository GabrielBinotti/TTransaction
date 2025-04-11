<?php

declare(strict_types=1);

namespace Sbintech\Transaction\Crud;


final class TSqlDelete extends TSqlInstruction
{

    public function getPreparedInstruction(string $driver = ""): string
    {
        
        $this->sql = "DELETE FROM {$this->entity}";
        if ($this->criteria) {
            foreach($this->criteria->getValues() as $key => $value){
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