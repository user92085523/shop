<?php

namespace App\MyHelper\Class;

class QueryBuilder
{
    public static function BuildQuery($query_ingredients)
    {
        $query = $query_ingredients['class']::query();

        foreach ($query_ingredients['inputs'] as $unused => $input) {
            if (! (isset($input['column']) && isset($input['method']) && isset($input['operator']) && isset($input['value']) && $input['value'] !== '')) {
                continue;
            }

            if ($input['operator'] === 'like') {
                $query = $query->{$input['method']}($input['column'], $input['operator'],"%{$input['value']}%");
            } elseif ($input['operator'] === '=') {
                $query = $query->{$input['method']}($input['column'], $input['operator'], $input['value']);
            }
        }

        return $query;
    }
}