<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Reservation extends Model
{
    use HasFactory;

    protected $fillable = [
        'salle_id',
        'user_id',
        'date_debut',
        'date_fin',
        'objet',
        'nombre_participants',
        'statut',
        'notes',
        'montant_total',
    ];

    protected $casts = [
        'date_debut' => 'datetime',
        'date_fin' => 'datetime',
        'montant_total' => 'decimal:2',
    ];

    const STATUT_EN_ATTENTE = 'en_attente';
    const STATUT_CONFIRMEE = 'confirmee';
    const STATUT_ANNULEE = 'annulee';
    const STATUT_TERMINEE = 'terminee';

    public static function getStatuts(): array
    {
        return [
            self::STATUT_EN_ATTENTE => 'En attente',
            self::STATUT_CONFIRMEE => 'Confirmée',
            self::STATUT_ANNULEE => 'Annulée',
            self::STATUT_TERMINEE => 'Terminée',
        ];
    }

    public function salle(): BelongsTo
    {
        return $this->belongsTo(Salle::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function getDureeHeuresAttribute(): float
    {
        return $this->date_debut->diffInMinutes($this->date_fin) / 60;
    }

    public function calculerMontant(): float
    {
        if ($this->salle) {
            return $this->duree_heures * $this->salle->tarif_heure;
        }
        return 0;
    }

    protected static function booted()
    {
        static::creating(function ($reservation) {
            if (!$reservation->montant_total && $reservation->salle_id) {
                $reservation->montant_total = $reservation->calculerMontant();
            }
        });
    }
}
