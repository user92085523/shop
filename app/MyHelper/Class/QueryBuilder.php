<?php

namespace App\MyHelper\Class;

use App\Models\User;

class QueryBuilder
{
    public static function BuildQuery($query_ingredients)
    {
        $query = $query_ingredients['class']::query();

        // dump($query_ingredients);

        foreach ($query_ingredients['inputs'] as $unused => $input) {
            $column = $input['column'];

            for ($i=0; $i < count($input['method']); $i++) { 
                $method = $input['method'][$i];

                if ($method === '') continue;

                if (in_array($method, ['onlyTrashed', 'withTrashed'])) {
                    $query = self::onlyMethod($query, $method);
                } elseif (in_array($method, ['where','whereDate'])) {
                    $operator = $input['operator'][$i];
                    $value = $input['value'][$i];

                    if ($operator === '' || $value === '') continue;

                    $query = self::unnamed($query, $column, $method, $operator, $value);
                }
            }
        }

        return $query;
    }

    private static function onlyMethod(&$query, $method)
    {
        return $query->$method();
    }

    private static function unnamed(&$query, $column, $method, $operator, $value)
    {
        if ($operator === 'like') {
            $value = "%$value%";
        }

        return $query->$method($column, $operator, $value);
    }
}