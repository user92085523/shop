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

    public static function getModelNameLowerCase()
    {
        return 'user';
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
            'loginId' => [
                'label' => 'ログインID',
                'childs' => [
                    [
                        'label' => '部分一致',
                        'operator' => 'like',
                        'value' => '',
                        'html_tag' => 'input',
                        'child' => false,
                    ],
                    [
                        'label' => '完全一致',
                        'operator' => '=',
                        'value' => '',
                        'html_tag' => 'input',
                        'child' => false,
                    ],
                ],
            ],
            // 'created_at' => [
            //     'label' => '作成日',
            //     'child' => [
            //         [
            //             'label' => '以前',
            //             'operator' => '<',
            //             'html_tag' => 'input',
            //         ],
            //         [
            //             'label' => '以降',
            //             'operator' => '>',
            //             'html_tag' => 'input',
            //         ],
            //     ],
            // ],
            // 'updated_at' => [
            //     'label' => '更新日',
            //     'child' => [
            //         [
            //             'label' => '何日以前',
            //             'operator' => '>',
            //             'html_tag' => 'input',
            //         ],
            //         [
            //             'label' => '部分一致',
            //             'operator' => 'like',
            //             'html_tag' => 'input',
            //         ],
            //     ],
            // ],
            // 'deleted_at' => [
            //     'label' => '削除',
            //     'child' => [
            //         [
            //             'label' => '1',
            //             'operator' => '<',
            //             'html_tag' => 'input',
            //         ],
            //         [
            //             'label' => '2',
            //             'operator' => '>',
            //             'html_tag' => 'input',
            //         ],
            //     ],
            // ],
        ];
    }

    public static function buildQuery($column, $keyword)
    {
        if ($keyword === '') {
            return User::all();
        }
        return User::where($column, $keyword)->get();
    }
}
