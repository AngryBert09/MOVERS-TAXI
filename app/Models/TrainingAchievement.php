<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TrainingAchievement extends Model
{
    use HasFactory;

    // Define the table associated with the model
    protected $table = 'training_achievements';

    // Define the primary key if it's not 'id'
    protected $primaryKey = 'id';

    // Define the fillable fields for mass assignment
    protected $fillable = [
        'employee_id',
        'type',
        'training_date',
        'training_provider',
        'status'
    ];


    // Mutator to ensure training_date is stored in the correct format
    public function setTrainingDateAttribute($value)
    {
        $this->attributes['training_date'] = \Carbon\Carbon::parse($value)->format('Y-m-d');
    }

    // Accessor for training_date to ensure it's in a readable format
    public function getTrainingDateAttribute($value)
    {
        return \Carbon\Carbon::parse($value)->format('F j, Y'); // Example: March 15, 2025
    }
}
