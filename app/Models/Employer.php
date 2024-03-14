<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Employer extends Model
{
    protected $table = 'employers';

    protected $fillable = [
        'user_id', 'company_name', 'company_size',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function offers() 
    {
        return $this->hasMany(Offer::class, 'employer_id');
    }

    public function reviews()
    {
        return $this->hasMany(EmployerReview::class);
    }

    
}
