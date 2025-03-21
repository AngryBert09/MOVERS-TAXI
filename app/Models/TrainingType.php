<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TrainingType extends Model
{
    protected $fillable = ['type_name', 'description', 'status'];

    public function trainings()
    {
        return $this->hasMany(Training::class);
    }
}
