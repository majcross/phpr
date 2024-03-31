<?php

declare(strict_types=1);

namespace Framework\Rules;

use Framework\Contracts\RuleInterface;

class MatchRule implements RuleInterface
{
    public function validate(array $data, string $field, array $params): bool
    {
        // Grap the field the rule is applied on 
        $fieldOne = $data[$field];
        // Grap the params to compare with
        $fieldTwo = $data[$params[0]];
        return $fieldOne === $fieldTwo;
    }

    public function getMessage(array $data, string $field, array $params): string
    {
        return "Does not match {$params[0]} field";
    }
}
