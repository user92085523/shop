<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Enums\Models\User\Role;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Parables\NanoId\GeneratesNanoId;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, GeneratesNanoId;

    protected $table = 'user';
    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'loginId',
        // 'email',
        'password',
        'name',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'role' => Role::class,
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            // 'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function employee(): HasOne
    {
        return $this->hasOne(Employee::class);
    }

    public static function nanoIdColumn()
    {
        return 'publicId';
    }

    public static function getSearchFormOption()
    {
        return [
            'base' => [
                'label' => 'ユーザー',
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
                        'input/common.input_text',
                    ],
                ],
                [
                    'label' => 'ロール',
                    'flag' => 0b1110,
                    'data' => [
                        'column' => 'role',
                        'method' => 'where',
                        'operator' => '=',
                    ],
                    'next' => [
                        'select/role',
                    ],
                ],
                [
                    'label' => '作成日',
                    'flag' => 0b1000,
                    'data' => [
                        'column' => 'created_at',
                    ],
                    'next' => [
                        'select/role',
                    ],
                ]
            ],
            'role' => [
                [
                    'label' => '従業員',
                    'flag' => 0b0001,
                    'data' => [
                        'value' => Role::Employee->name,
                    ],
                ],
                [
                    'label' => '顧客',
                    'flag' => 0b0001,
                    'data' => [
                        'value' => Role::Customer->name,
                    ],
                ],
            ],
            'common' => [
                'input_text' => [
                    [
                        'label' => '検索キーワードを入力',
                        'flag' => 0b0101,
                        'data' => [
                            'method' => 'where',
                            'value' => '',
                        ],
                        'next' => [
                            'select/common.operator.text',
                        ],
                    ],
                ],
                'operator' => [
                    'text' => [
                        [
                            'label' => '部分一致',
                            'flag' => 0b0010,
                            'data' => [
                                'operator' => 'like',
                            ],
                        ],
                        [
                            'label' => '完全一致',
                            'flag' => 0b0010,
                            'data' => [
                                'operator' => '=',
                            ],
                        ]
                    ],
                ],
            ],
        ];
    }
}
