<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployerReview extends Model
{
    use HasFactory;

    protected $fillable = [
        'employer_id', 
        'candidate_id', 
        'feedback', 
        'rating', 
        'parent_id'
    ];

    public function employer()
    {
        return $this->belongsTo(Employer::class);
    }

    public function candidate()
    {
        return $this->belongsTo(Candidate::class);
    }

    public function replies()
    {
        return $this->hasMany(EmployerReview::class, 'parent_id');
    }

    public function parent()
    {
        return $this->belongsTo(EmployerReview::class, 'parent_id');
    }

}
