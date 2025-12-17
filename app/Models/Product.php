<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'active',
        'order',
    ];

    protected $casts = [
        'active' => 'boolean',
        'order' => 'integer',
    ];

    /**
     * Mensagens de contato relacionadas a este produto
     */
    public function contactMessages(): HasMany
    {
        return $this->hasMany(ContactMessage::class);
    }

    /**
     * Scope para produtos ativos
     */
    public function scopeActive($query)
    {
        return $query->where('active', true);
    }

    /**
     * Scope para ordenação padrão
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('order')->orderBy('name');
    }
}
