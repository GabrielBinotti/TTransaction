<?php
declare(strict_types = 1);

namespace Sbintech\Transaction\Classes;


class TFilter extends TExpression
{

    private string $variable;
    private string $operator;
    private mixed $value;

    public function __construct(string $variable, string $operator, mixed $value)
    {
        $this->variable = $variable;
        $this->operator = strtoupper(string: $operator);
        $this->value = $value;
    }


    public function dump()
    {
        return "{$this->variable} {$this->operator} :w_{$this->variable}";
    }

    public function getValues(): array
    {
        return ["w_{$this->variable}" => $this->value];
    }
}