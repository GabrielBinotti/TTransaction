<?php

declare(strict_types=1);
namespace Sbintech\Transaction\Classes;
use Sbintech\Transaction\Classes\TExpression;

class TColumn extends TExpression
{
    private string $column;

    public function __construct(string $column)
    {
        $this->column = $column;
    }

    public function dump()
    {
        return "{$this->column}";
    }
}