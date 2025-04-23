<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Customer extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'customer';

    protected $fillable = [
        'name',
        'user_id',
        'phoneNumber',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public static function getModelCreationFormObj()
    {
        $common = 'customer_form.inputs.';
        
        return [
            'name' => [
                'label' => '名前',
                'html_tag' => 'input',
                'model_mod' => '.change',
                'var_name' => $common . 'name',
            ],
            'phoneNumber' => [
                'label' => '電話番号',
                'html_tag' => 'input',
                'model_mod' => '.live.debounce.700ms',
                'var_name' => $common . 'phoneNumber',
            ],
        ];
    }
}
