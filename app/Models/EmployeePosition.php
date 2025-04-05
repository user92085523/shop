<?php

namespace App\Models;

use App\Enums\Models\Employee\Position;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployeePosition extends Model
{
    use HasFactory;
    protected $table = 'employee_position';

    protected $casts = [
        'position' => Position::class,
    ];
}
