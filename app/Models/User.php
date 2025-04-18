<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Enums\Models\User\Role;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Parables\NanoId\GeneratesNanoId;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, GeneratesNanoId, SoftDeletes;

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
                        'none/loop.entry_point.id',
                    ],
                ],
                [
                    'label' => 'ログインID',
                    'flag' => 0b1000,
                    'data' => [
                        'column' => 'loginId',
                    ],
                    'next' => [
                        'none/loop.entry_point.loginId',
                    ],
                ],
                [
                    'label' => 'ロール',
                    'flag' => 0b1000,
                    'data' => [
                        'column' => 'role',
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
                        'none/loop.entry_point.created_at',
                    ],
                ],
                [
                    'label' => '更新日',
                    'flag' => 0b1000,
                    'data' => [
                        'column' => 'updated_at',
                    ],
                    'next' => [
                        'none/loop.entry_point.updated_at',
                    ],
                ],
                [
                    'label' => 'データの状態',
                    'flag' => 0b1011,
                    'next' => [
                        'select/deleted_at',
                    ],
                ],
            ],
            'role' => [
                [
                    'label' => '従業員',
                    'flag' => 0b0111,
                    'data' => [
                        'method' => 'where',
                        'operator' => '=',
                        'value' => Role::Employee->name,
                    ],
                ],
                [
                    'label' => '顧客',
                    'flag' => 0b0111,
                    'data' => [
                        'method' => 'where',
                        'operator' => '=',
                        'value' => Role::Customer->name,
                    ],
                ],
                [
                    'label' => '管理者',
                    'flag' => 0b0111,
                    'data' => [
                        'method' => 'where',
                        'operator' => '=',
                        'value' => Role::Admin->name,
                    ],
                ],
            ],
            'deleted_at' => [
                [
                    'label' => '削除済みのみ',
                    'flag' => 0b0100,
                    'data' => [
                        'method' => 'onlyTrashed',
                    ],
                ],
                [
                    'label' => 'すべて(削除済み含めた)',
                    'flag' => 0b0100,
                    'data' => [
                        'method' => 'withTrashed',
                    ],
                ],
            ],
            'common' => [
                'input' => [
                    'text' => [
                        [
                            'label' => 'キーワードを入力',
                            'flag' => 0b0001,
                            'data' => [
                                'value' => '',
                            ],
                        ],
                    ],
                    'num' => [
                        [
                            'label' => '数字を入力',
                            'flag' => 0b0001,
                            'data' => [
                                'value' => '',

                            ],
                        ],
                    ],
                    'date' => [
                        [
                            'label' => 'YYYY-MM-DD',
                            'flag' => 0b0001,
                            'data' => [
                                'value' => '',
                            ],
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
                        ],
                    ],
                    'num' => [
                        [
                            'label' => '以下',
                            'flag' => 0b0010,
                            'data' => [
                                'operator' => '<=',
                            ],
                        ],
                        [
                            'label' => '以上',
                            'flag' => 0b0010,
                            'data' => [
                                'operator' => '>=',
                            ],
                        ],
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
                        ],
                    ],
                    'date' => [
                        [
                            'label' => '以前',
                            'flag' => 0b0010,
                            'data' => [
                                'operator' => '<=',
                            ],
                        ],
                        [
                            'label' => '以降',
                            'flag' => 0b0010,
                            'data' => [
                                'operator' => '>=',
                            ],
                        ],
                        [
                            'label' => '完全一致',
                            'flag' => 0b0010,
                            'data' => [
                                'operator' => '=',
                            ],
                        ],
                    ]
                ],
                'date' => [
                    [
                        'label' => '年月日を入力する',
                        'flag' => 0b0100,
                        'data' => [
                            'method' => 'whereDate',
                        ],
                        'next' => [
                            'input/common.input.date',
                            'select/common.operator.date',
                            'select/loop.end_point.created_at',
                        ],
                    ],
                    // [
                    //     'label' => '年を選択',
                    //     'flag' => 0b0100,
                    //     'data' => [
                    //         'method' => 'whereYear',
                    //     ],
                    //     'next' => [
                    //         'select/common.year',
                    //         'select/loop.end_point.created_at',
                    //     ],
                    // ],
                ],
                'year' => [
                    [
                        'label' => '2025',
                    ],
                ],
            ],
            'loop' => [
                'entry_point' => [
                    'id' => [
                        [
                            'flag' => 0b0100,
                            'data' => [
                                'method' => 'where',
                            ],
                            'next' => [
                                'input/common.input.num',
                                'select/common.operator.num',
                                'select/loop.end_point.id',
                            ],
                        ],
                    ],
                    'loginId' => [
                        [
                            'flag' => 0b0100,
                            'data' => [
                                'method' => 'where',
                            ],
                            'next' => [
                                'input/common.input.text',
                                'select/common.operator.text',
                                'select/loop.end_point.loginId',
                            ],
                        ],
                    ],
                    'created_at' => [
                        [
                            'next' => [
                                'select/common.date',
                            ],
                        ],
                    ],
                    'updated_at' => [
                        [
                            'next' => [
                                'select/common.date',
                            ]
                        ]
                    ]
                ],
                'end_point' => [
                    'id' => [
                        [
                            'label' => '-追加しない-',
                        ],
                        [
                            'label' => '+追加する+',
                            'next' => [
                                'none/loop.entry_point.id',
                            ],
                        ],
                    ],
                    'loginId' => [
                        [
                            'label' => '-追加しない-',
                        ],
                        [
                            'label' => '+追加する+',
                            'next' => [
                                'none/loop.entry_point.loginId',
                            ],
                        ],
                    ],
                    'created_at' => [
                        [
                            'label' => '-追加しない-',
                        ],
                        [
                            'label' => '+追加する+',
                            'next' => [
                                'none/loop.entry_point.created_at',
                            ],
                        ],
                    ],
                    'updated_at' => [
                        [
                            'label' => '-追加しない-',
                        ],
                        [
                            'label' => '+追加する+',
                            'next' => [
                                'none/loop.entry_point.updated_at',
                            ],
                        ],
                    ]
                ],
            ],
        ];
    }
}
