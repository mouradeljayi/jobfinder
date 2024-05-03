<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;

class Offer extends Model
{
    use HasFactory, SoftDeletes;

    const TYPE_PART_TIME = "part time";
    const TYPE_FULL_TIME = "full time";
    const TYPE_INTERNSHIP = "internship";


    protected $fillable = [
        'employer_id',
        'title',
        'description',
        'location',
        'type',
        'salary',
        'experience',
        'deadline',
        'skills'
    ];
    protected $casts = [
        'skills' => 'array',
    ];
    public static $offerType = [
        self::TYPE_PART_TIME,
        self::TYPE_FULL_TIME,
        self::TYPE_INTERNSHIP,

    ];

    public static function filterOffers(array $filters): Builder
    {
        $query = self::query();

        if (!empty($filters['location'])) {
            $query->where('location', 'LIKE', '%' . $filters['location'] . '%');
        }

        if (!empty($filters['type'])) {
            $query->where('type', $filters['type']);
        }

        if (!empty($filters['salary_range'])) {
            [$min, $max] = explode('-', $filters['salary_range']);
            $query->whereBetween('salary', [(int)$min, (int)$max]);
        }

        if (!empty($filters['experience'])) {
            $query->where('experience', $filters['experience']);
        }

        // You can add more filters here as needed
        $query->orderBy('created_at', 'desc');

        return $query;
    }

    public function employer()
    {
        return $this->belongsTo(User::class, 'employer_id');
    }

    public function candidacies()
    {
        return $this->hasMany(Candidacy::class);
    }
}
