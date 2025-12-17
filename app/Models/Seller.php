<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Seller extends Model
{
    use HasFactory;

    protected $fillable = [
        'distributor_id',
        'name',
        'email',
        'phone',
        'whatsapp',
        'position',
    ];

    public function distributor(): BelongsTo
    {
        return $this->belongsTo(Distributor::class);
    }
}
