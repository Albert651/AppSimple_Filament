<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Salle extends Model
{
    use HasFactory;

    protected $fillable = [
        'nom',
        'capacite',
        'equipements',
        'description',
        'tarif_heure',
        'disponible',
        'image',
    ];

    protected $casts = [
        'equipements' => 'array',
        'disponible' => 'boolean',
        'tarif_heure' => 'decimal:2',
    ];

    public function reservations(): HasMany
    {
        return $this->hasMany(Reservation::class);
    }

    public function isDisponible(string $dateDebut, string $dateFin): bool
    {
        return !$this->reservations()
            ->where('statut', '!=', 'annulee')
            ->where(function ($query) use ($dateDebut, $dateFin) {
                $query->whereBetween('date_debut', [$dateDebut, $dateFin])
                    ->orWhereBetween('date_fin', [$dateDebut, $dateFin])
                    ->orWhere(function ($q) use ($dateDebut, $dateFin) {
                        $q->where('date_debut', '<=', $dateDebut)
                            ->where('date_fin', '>=', $dateFin);
                    });
            })
            ->exists();
    }
}

