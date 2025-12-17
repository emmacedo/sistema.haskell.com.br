<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SearchStatistic extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'city_id',
        'search_term',
        'search_type',
        'has_result',
        'results_count',
        'ip_address',
        'user_agent',
    ];

    protected $casts = [
        'has_result' => 'boolean',
        'results_count' => 'integer',
        'created_at' => 'datetime',
    ];

    public function city(): BelongsTo
    {
        return $this->belongsTo(City::class);
    }

    public function scopeWithResults($query)
    {
        return $query->where('has_result', true);
    }

    public function scopeWithoutResults($query)
    {
        return $query->where('has_result', false);
    }

    public function scopeByCep($query)
    {
        return $query->where('search_type', 'cep');
    }

    public function scopeByCity($query)
    {
        return $query->where('search_type', 'city');
    }
}
