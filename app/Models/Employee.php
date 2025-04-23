<?php

namespace App\Models;

use App\Enums\Models\EmployeePosition\Name;
use App\Enums\Models\User\Role;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class Employee extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'employee';

    protected $fillable = [
        'name',
        'user_id',
        'employee_position_id',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function employee_position(): BelongsTo
    {
        return $this->belongsTo(EmployeePosition::class);
    }

    public static function getModelCreationFormObj()
    {
        $common = 'employee_form.inputs.';
        
        return [
            'name' => [
                'label' => '名前',
                'html_tag' => 'input',
                'model_mod' => '.change',
                'var_name' => $common . 'name',
            ],
            'employee_position' => [
                'label' => '役職',
                'html_tag' => 'select',
                'model_mod' => '.change',
                'var_name' => $common . 'employee_position',
                'elements' => Name::getCreationObjNameElements(),
            ],
        ];
    }

    public static function getCreationValidationMsg(&$Employee_creation_obj, $fn_partial_name, $msg_valid)
    {
        $msg = [];

        foreach ($Employee_creation_obj['msg'] as $key => $value) {
            $msg[$key] = self::{$fn_partial_name . $key}($Employee_creation_obj['input'], $key, $msg_valid);
        }

        return $msg;
    }

    public static function getCreationValidationMsg_name($input, $key, $msg_valid)
    {
        $name = $input[$key];

        if ($name === '' || $name === null) {
            return "*入力は必須です*";
        }

        if (strlen($name) < 4 || strlen($name) > 45) {
            return '*2~15文字の範囲内のみ有効です(全角の場合)*';
        }

        return $msg_valid;
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
