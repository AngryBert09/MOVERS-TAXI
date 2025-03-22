<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PerformanceEvaluation extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'trainee_competencies';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'trainee_id',
        'evaluation_date',
        'customer_experience',
        'marketing',
        'management',
        'administration',
        'presentation_skill',
        'quality_of_work',
        'efficiency',
        'integrity',
        'professionalism',
        'team_work',
        'critical_thinking',
        'conflict_management',
        'attendance',
        'ability_to_meet_deadline',
        'status',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'evaluation_date' => 'date',
    ];

    /**
     * Get the trainee associated with the performance appraisal.
     */
    public function jobApplication()
    {
        return $this->belongsTo(JobApplication::class, 'id');
    }
}
