<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JobPosting extends Model
{
    protected $fillable = [
        'job_title',
        'department',
        'job_location',
        'no_of_vacancies',
        'experience',
        'age',
        'salary_from',
        'salary_to',
        'job_type',
        'status',
        'start_date',
        'expired_date',
        'description'
    ];

    // Relationship: One JobPosting has many JobApplications
    public function applications()
    {
        return $this->hasMany(JobApplication::class);
    }

    public function department()
    {
        return $this->belongsTo(Department::class, 'id');
    }
}
