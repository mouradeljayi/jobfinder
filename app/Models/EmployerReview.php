<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployerReview extends Model
{
    use HasFactory;

    protected $fillable = ['employer_id', 'candidate_id', 'feedback', 'rating'];

    public function employer()
    {
        return $this->belongsTo(Employer::class);
    }

    public function candidate()
    {
        return $this->belongsTo(Candidate::class);
    }

}
