<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Distributor extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * Boot do model: registra evento para soft delete em cascata dos sellers.
     * Como o Distributor usa SoftDeletes, o CASCADE do banco nunca é acionado
     * (o registro não é fisicamente removido), então precisamos tratar via Eloquent.
     */
    protected static function boot()
    {
        parent::boot();

        // Ao soft-deletar um distribuidor, soft-deleta todos os seus vendedores
        static::deleting(function (Distributor $distributor) {
            $distributor->sellers()->delete();
        });

        // Ao restaurar um distribuidor, restaura todos os seus vendedores
        static::restoring(function (Distributor $distributor) {
            $distributor->sellers()->withTrashed()->restore();
        });
    }

    protected $fillable = [
        'company_name',
        'trade_name',
        'cnpj',
        'email',
        'phone',
        'phone2',
        'whatsapp',
        'website',
        'cep',
        'logradouro',
        'numero',
        'complemento',
        'bairro',
        'cidade',
        'estado',
        'status',
        'email_verified_at',
        'verification_code',
        'verification_code_expires_at', // Data/hora de expiração do código de verificação
    ];

    protected $hidden = [
        'verification_code',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'verification_code_expires_at' => 'datetime', // Cast automático para Carbon
    ];

    public function sellers(): HasMany
    {
        return $this->hasMany(Seller::class);
    }

    public function cities(): BelongsToMany
    {
        return $this->belongsToMany(City::class)
            ->withTimestamps();
    }

    public function contactMessages(): HasMany
    {
        return $this->hasMany(ContactMessage::class);
    }

    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeRejected($query)
    {
        return $query->where('status', 'rejected');
    }

    public function isApproved(): bool
    {
        return $this->status === 'approved';
    }

    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    public function isRejected(): bool
    {
        return $this->status === 'rejected';
    }

    public function isEmailVerified(): bool
    {
        return $this->email_verified_at !== null;
    }
}
