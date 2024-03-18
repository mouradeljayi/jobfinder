<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Offer extends Model
{
    use HasFactory;
    use SoftDeletes;

    const PART_TIME = 'part time';
    const FULL_TIME = 'full time';
    const INTERNSHIP = 'internship';


    protected $fillable = [
        'id_employer',
        'title',
        'description',
        'location',
        'type',
        'salary',
        'experience',
        'deadline',

    ];
    public static $offerType = [
        self::PART_TIME,
        self::FULL_TIME,
        self::INTERNSHIP,

    ];
    public function employer()
    {
        return $this->belongsTo(User::class, 'employer_id');
    }

    public function candidacies()
    {
        return $this->hasMany(Candidacy::class);
    }
}