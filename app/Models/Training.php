<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Training extends Model
{
    use HasFactory;

    protected $table = 'trainings';
    protected $fillable = [
        'training_type',
        'trainer',
        'trainee_id',
        'training_cost',
        'start_date',
        'end_date',
        'description',
        'status',
    ];

    protected $casts = [
        'employees' => 'array', // Convert JSON to array
        'start_date' => 'date',
        'end_date' => 'date',
        'training_cost' => 'float',
    ];

    // Relationship (if employees are stored as IDs)
    // public function assignedEmployees()
    // {
    //     return $this->hasMany(Employee::class, 'id', 'employees');
    // }

    public function trainer()
    {
        return $this->belongsTo(Trainer::class);
    }

    public function trainingType()
    {
        return $this->belongsTo(TrainingType::class);
    }



    public function trainee()
    {
        return $this->belongsTo(JobApplication::class, 'trainee_id');
    }
}
