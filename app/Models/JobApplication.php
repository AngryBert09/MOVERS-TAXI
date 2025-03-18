<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JobApplication extends Model
{
    protected $fillable = ['name', 'email', 'phone', 'apply_date', 'status', 'resume',  'job_posting_id'];



    // Relationship: A JobApplication belongs to a JobPosting
    public function jobPosting()
    {
        return $this->belongsTo(JobPosting::class);
    }
}
