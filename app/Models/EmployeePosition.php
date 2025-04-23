<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class EmployeePosition extends Model
{
    use HasFactory;
    protected $table = 'employee_position';

    public function employee(): HasOne
    {
        return $this->hasOne(Employee::class);
    }
}
