<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PersonalInformation extends Model
{
    use HasFactory;

    protected $table = 'personal_information';

    protected $fillable = [
        'application_id',
        'first_name',
        'last_name',
        'birth_date',
        'gender',
        'address',
        'phone_number',
    ];


    public function jobApplication()
    {
        return $this->belongsTo(JobApplication::class, 'application_id', 'id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
