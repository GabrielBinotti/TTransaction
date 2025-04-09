<?php
declare(strict_types=1);

namespace Sbintech\Transaction\Classes;
class TCriteria extends TExpression
{

    private array $expressions;
    private array $operators;
    private array $properties;

    public function add(TExpression $expression, string $operator = self::AND_OPERATOR)
    {
        if (empty($this->operators)) {
            $operator = "";
        }

        $this->expressions[] = $expression;
        $this->operators[] = $operator;
    }



    public function dump()
    {
        $result = "";
        if (is_array(value: $this->expressions)) {
            foreach ($this->expressions as $key => $value) {
                $operator = $this->operators[$key];
                $result .= $operator . $value->dump() . " ";
            }
            $result = trim(string: $result);
            return "({$result})";
        }
    }

    public function getValues(): array
    {
        $values = [];

        foreach ($this->expressions as $expression) {
            $values = array_merge($values, $expression->getValues());
        }

        return $values;
    }
}