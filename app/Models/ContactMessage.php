<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ContactMessage extends Model
{
    use HasFactory;

    protected $fillable = [
        'distributor_id',
        'city_id',
        'sender_name',
        'sender_email',
        'sender_phone',
        'sender_city',
        'sender_state',
        'product_id',
        'seller_id',
        'message',
        'read_at',
    ];

    protected $casts = [
        'read_at' => 'datetime',
    ];

    /**
     * Distribuidor que recebeu a mensagem
     */
    public function distributor(): BelongsTo
    {
        return $this->belongsTo(Distributor::class);
    }

    /**
     * Cidade da busca (quando aplicável)
     */
    public function city(): BelongsTo
    {
        return $this->belongsTo(City::class);
    }

    /**
     * Produto de interesse
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Vendedor que recebeu o contato
     */
    public function seller(): BelongsTo
    {
        return $this->belongsTo(Seller::class);
    }

    /**
     * Scope para mensagens não lidas
     */
    public function scopeUnread($query)
    {
        return $query->whereNull('read_at');
    }

    /**
     * Scope para mensagens lidas
     */
    public function scopeRead($query)
    {
        return $query->whereNotNull('read_at');
    }

    /**
     * Marca a mensagem como lida
     */
    public function markAsRead(): void
    {
        if ($this->read_at === null) {
            $this->update(['read_at' => now()]);
        }
    }

    /**
     * Verifica se a mensagem foi lida
     */
    public function isRead(): bool
    {
        return $this->read_at !== null;
    }
}
