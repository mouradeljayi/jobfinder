<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Candidate extends Model
{
    protected $table = 'candidates';

    protected $fillable = [
        'user_id', 
        'first_name', 
        'last_name', 
        'education_level', 
        'experience'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function candidacies() 
    {
        return $this->hasMany(Candidacy::class, 'candidate_id');
    }
    
    public function reviews()
    {
        return $this->hasMany(EmployerReview::class);
    }


}
