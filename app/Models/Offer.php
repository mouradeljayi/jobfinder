<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Offer extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $dates = ['deleted_at'];
    
    const TYPE_PART_TIME = 'part time';
    const TYPE_FULL_TIME = 'full time';
    const TYPE_INTERNSHIP = 'internship';


    protected $fillable = [
                'employer_id',
                'title',
                'description',
                'location',
                'type',
                'salary',
                'experience',
                'deadline',
    ];

    public static $offerType = [
        self::TYPE_PART_TIME,
        self::TYPE_FULL_TIME,
        self::TYPE_INTERNSHIP,

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
