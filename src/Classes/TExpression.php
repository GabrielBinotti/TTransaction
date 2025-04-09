<?php

namespace Sbintech\Transaction\Classes;

abstract class TExpression
{
    const AND_OPERATOR  = "AND ";
    const OR_OPERATOR   = "OR ";

    public function getValues(): array
    {
        return [];
    }
}