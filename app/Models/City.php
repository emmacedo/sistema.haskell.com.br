<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class City extends Model
{
    use HasFactory;

    protected $fillable = [
        'state_id',
        'name',
        'ibge_code',
    ];

    public function state(): BelongsTo
    {
        return $this->belongsTo(State::class);
    }

    public function distributors(): BelongsToMany
    {
        return $this->belongsToMany(Distributor::class)
            ->withTimestamps();
    }

    public function contactMessages(): HasMany
    {
        return $this->hasMany(ContactMessage::class);
    }

    public function searchStatistics(): HasMany
    {
        return $this->hasMany(SearchStatistic::class);
    }
}
