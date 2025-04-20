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


    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'trainee_id',
        'category',
        'criteria',
        'rating',
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
