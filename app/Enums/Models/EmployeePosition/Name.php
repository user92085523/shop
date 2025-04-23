<?php

namespace App\Enums\Models\EmployeePosition;

enum Name: string
{
    case Manager = 'Manager';
    case Regular = 'Regular';
    case NonRegular = 'NonRegular';

    public function ja_JP()
    {
        return match ($this) {
            Name::Manager => 'マネージャー',
            Name::Regular => '正社員',
            Name::NonRegular => '非正規',
        };
    }

    public static function getCreationObjNameElements()
    {
        return [
            [
                'label' => Name::NonRegular->ja_JP(),
                'value' => Name::NonRegular->value,
            ],
            [
                'label' => Name::Regular->ja_JP(),
                'value' => Name::Regular->value,
            ],
            [
                'label' => Name::Manager->ja_JP(),
                'value' => Name::Manager->value,
            ],
        ];
    }
}
