<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Employee extends Model
{
    use HasFactory;
    protected $table = 'employee';

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public static function getSearchFormOption()
    {
        return [
            'base' => [
                'label' => '従業員',
                'class' => self::class,
            ],
            'columns' => [
                [
                    'label' => 'ID',
                    'flag' => 0b1000,
                    'data' => [
                        'column' => 'id',
                    ],
                    'next' => [
                        // 'select/common.columns',
                    ],
                ],
                [
                    'label' => '作成日',
                    'flag' => 0b1000,
                    'data' => [
                        'column' => 'created_at',
                    ],
                    'next' => [
                        // 'select/role',
                    ],
                ]
            ],
        ];
    }
}
