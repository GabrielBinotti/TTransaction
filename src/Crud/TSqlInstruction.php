<?php

declare(strict_types=1);
namespace Sbintech\Transaction\Crud;

use Sbintech\Transaction\Classes\TColumn;
use Sbintech\Transaction\Classes\TCriteria;
use Sbintech\Transaction\Classes\TJoinGroup;

abstract class TSqlInstruction
{
    protected $sql;
    protected $criteria;
    protected string $entity;
    protected $column;
    protected $join;

    final public function setEntity(string $entity)
    {
        $this->entity = $entity;
    }

    final public function setCriteria(TCriteria $criteria)
    {
        $this->criteria = $criteria;
    }

    final public function setColumn(TColumn $column)
    {
        $this->column = $column;
    }

    final public function setJoin(TJoinGroup $join_group)
    {
        $this->join = $join_group;
    }

    final public function getEntity(): string
    {
        return $this->entity;
    }

    public function setRowData(string $column, mixed $value)
    {
        if (is_string(value: $value)) {
            $value = addslashes(string: $value);
            $this->column_values[$column] = "{$value}";
        } else if (is_bool(value: $value)) {
            $this->column_values[$column] = $value ? 'TRUE' : 'FALSE';
        } else if (isset($value)) {
            $this->column_values[$column] = $value;
        } else {
            $this->column_values[$column] = NULL;
        }
    }

    public function bindValues(\PDOStatement $stmt)
    {
        if(isset($this->column_values)){
            foreach ($this->column_values as $column => $value) {
                $param = match (true) {
                    is_int(value: $value) => \PDO::PARAM_INT,
                    is_bool(value: $value) => \PDO::PARAM_BOOL,
                    is_null(value: $value) => \PDO::PARAM_NULL,
                    
                    default => \PDO::PARAM_STR,
                };
                
                $stmt->bindValue(param: ":$column", value: $value, type: $param);
            }
        }
        
    }

    abstract public function getPreparedInstruction():string;
    abstract public function execute(\PDO $pdo):mixed;
}