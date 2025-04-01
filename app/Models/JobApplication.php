<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JobApplication extends Model
{
    protected $fillable = ['name', 'email', 'phone', 'apply_date', 'status', 'resume',  'job_posting_id', 'application_code', 'user_id'];



    // Relationship: A JobApplication belongs to a JobPosting
    public function jobPosting()
    {
        return $this->belongsTo(JobPosting::class);
    }

    public function performanceEvaluations()
    {
        return $this->hasMany(PerformanceEvaluation::class, 'trainee_id');
    }

    public function personalInformation()
    {
        return $this->hasOne(PersonalInformation::class, 'application_id');
    }
}
