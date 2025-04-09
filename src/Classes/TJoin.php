<?php

declare(strict_types=1);
namespace Sbintech\Transaction\Classes;

class TJoin extends TExpression
{
    private string $table;
    private string $condition;
    private string $type;
    private string $value;

    public function __construct(string $table, string $condition, string $type)
    {
        $this->table = $table;
        $this->condition = $condition;
        $this->type = $type;
    }

    public function dump(): string
    {
        return "{$this->type} JOIN {$this->table} ON {$this->condition}";
    }
}