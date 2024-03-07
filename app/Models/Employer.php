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
}
