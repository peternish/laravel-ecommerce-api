<?php

namespace App\Models;

use App\Traits\HasLogs;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory, HasLogs;

    protected $fillable = [
        'user_id',
        'value',
        'status'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function scopeOpen($query)
    {
        $query->where('status', 0);
    }

    public function scopeClosed($query)
    {
        $query->where('status', 1);
    }
}
