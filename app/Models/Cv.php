<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cv extends Model
{
    use HasFactory;
    protected $fillable = [
        'candidate_id',
        'title',
        'cv_path',
        'education',
        'skills',
        'certifications',
        'languages',
        'experiences',
    ];


    protected $casts = [
        'education' => 'array',
        'skills' => 'array',
        'certifications' => 'array',
        'languages' => 'array',
        'experiences' => 'array',
    ];
    public function candidate()
    {
        return $this->belongsTo(User::class, 'candidate_id');
    }
}