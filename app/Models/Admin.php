<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Admin extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'admin';

    protected $fillable = [
        'name',
        'user_id',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public static function getHeaderMenuObj()
    {
        return [
            [
                'label' => 'ユーザー',
                'next' =>[
                    [
                        'label' => 'ユーザーの検索',
                        'full_path' => '/admin/user',
                    ],
                    [
                        'label' => 'ユーザーの作成',
                        'full_path' => '/admin/user/create',
                    ],
                ],
            ],
            [
                'label' => '商品',
                'next' => [
                    [
                        'label' => '商品の検索',
                        'full_path' => '/root',
                    ],
                ],
            ]
        ];
    }

    public static function getModelCreationFormObj()
    {
        $common = 'admin_form.inputs.';
        
        return [
            'name' => [
                'label' => '管理者専用',
                'html_tag' => 'input',
                'model_mod' => '.change',
                'var_name' => $common . 'name',
            ],
        ];
    }

    public static function getCreationValidationMsg($Admin_creation_obj, $fn_partial_name, $msg_valid)
    {
        $msg = [];

        foreach ($Admin_creation_obj['form'] as $key => $value) {
            $msg[$key] = self::{$fn_partial_name . $key}($Admin_creation_obj['input'], $key, $msg_valid);
        }

        return $msg;
    }

    public static function getCreationValidationMsg_name($input, $key, $msg_valid)
    {
        $input = $input[$key];

        if ($input === '' || $input === null) {
            return "*入力は必須です*";
        }

        if (strlen($input) < 4 || strlen($input) > 45) {
            return '*2~15文字の範囲内のみ有効です(全角の場合)*';
        }

        return $msg_valid;
    }
}
