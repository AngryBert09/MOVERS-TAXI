<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Trainer extends Model
{
    protected $fillable = ['first_name', 'last_name', 'role', 'email', 'phone', 'status', 'description', 'employee_id'];

    public function trainings()
    {
        return $this->hasMany(Training::class);
    }
}
