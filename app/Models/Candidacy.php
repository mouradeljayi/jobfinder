<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Candidacy extends Model
{
    use HasFactory;

    const STATUS_APPLIED = 'applied';
    const STATUS_REVIEWING = 'reviewing';
    const STATUS_ACCEPTED = 'accepted';
    const STATUS_REJECTED = 'rejected';

    protected $fillable = [
        'offer_id',
        'candidate_id',
        'status',
    ];

    public static $validStatuses = [
        self::STATUS_APPLIED,
        self::STATUS_REVIEWING,
        self::STATUS_ACCEPTED,
        self::STATUS_REJECTED,
    ];

    public function offer()
    {
        return $this->belongsTo(Offer::class);
    }

    public function candidate()
    {
        return $this->belongsTo(User::class, 'candidate_id');
    }

}
