<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ApplicantFile extends Model
{
    use HasFactory;

    protected $table = 'applicant_files';

    protected $fillable = [
        'application_id',
        'sss',
        'pagibig',
        'philhealth',
        'tin',
        'barangay_clearance',
    ];

    // Relationship to the JobApplication (if you want it)
    public function application()
    {
        return $this->belongsTo(JobApplication::class, 'application_id');
    }
}
