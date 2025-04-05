<?php

namespace App\Models;

use App\Enums\Models\EmployeePosition\Name;
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

    public static function getModelNameLowerCase()
    {
        return 'employee';
    }

    public static function getSearchInfo()
    {
        return [
            'id' => [
                'label' => 'ID',
                'childs' => [
                    [
                        'label' => '部分一致',
                        'operator' => 'like',
                        'value' => null,
                        'html_tag' => 'input',
                        'child' => false,
                    ],
                    [
                        'label' => '完全一致',
                        'operator' => '=',
                        'value' => null,
                        'html_tag' => 'input',
                        'child' => false,
                    ],
                ],
            ],
            'position_id' => [
                'label' => '役職',
                'childs' => [
                    [
                        'label' => Name::Manager->ja_JP(),
                        'operator' => '=',
                        'value' => Name::Manager->value,
                        'html_tag' => false,
                        'child' => false,
                    ],
                    [
                        'label' => Name::Regular->ja_JP(),
                        'operator' => '=',
                        'value' => Name::Regular->value,
                        'html_tag' => false,
                        'child' => false,
                    ],
                    [
                        'label' => Name::NonRegular->ja_JP(),
                        'operator' => '=',
                        'value' => Name::NonRegular->value,
                        'html_tag' => false,
                        'child' => false,
                    ],
                ]
            ],
            // 'name' => [
            //     'label' => '名前',
            //     'child' => [
            //         [
            //             'label' => '部分一致',
            //             'operator' => 'like',
            //             'html_tag' => 'input',
            //         ],
            //         [
            //             'label' => '完全一致',
            //             'operator' => '=',
            //             'html_tag' => 'input',
            //         ],
            //     ],
            // ],
            // 'created_at' => [

            // ],
            // 'updated_at' => [

            // ],
            // 'deleted_at' => [

            // ],
        ];
    }
}
