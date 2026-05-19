<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $fillable = [
        'user_id',
        'plan',
        'nominal',
        'metode',
        'bukti_path',
        'status',
        'catatan_admin',
        'confirmed_by',
        'confirmed_at',
    ];

    protected $casts = [
        'confirmed_at' => 'datetime',
        'nominal'      => 'decimal:2',
    ];

    // =========================================================
    // RELATIONSHIPS
    // =========================================================

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function confirmedBy()
    {
        return $this->belongsTo(User::class, 'confirmed_by');
    }

    // =========================================================
    // HELPERS
    // =========================================================

    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    public function isConfirmed(): bool
    {
        return $this->status === 'confirmed';
    }

    public function isRejected(): bool
    {
        return $this->status === 'rejected';
    }

    public function nominalFormatted(): string
    {
        return 'Rp ' . number_format((float) $this->nominal, 0, ',', '.');
    }

    public function planLabel(): string
    {
        return match ($this->plan) {
            'personal'    => 'Personal',
            'profesional' => 'Profesional',
            default       => ucfirst($this->plan),
        };
    }

    public function statusLabel(): string
    {
        return match ($this->status) {
            'pending'   => 'Menunggu Konfirmasi',
            'confirmed' => 'Terkonfirmasi',
            'rejected'  => 'Ditolak',
            default     => $this->status,
        };
    }

    public function statusColor(): string
    {
        return match ($this->status) {
            'pending'   => 'orange',
            'confirmed' => 'green',
            'rejected'  => 'red',
            default     => 'gray',
        };
    }

    // Harga per plan
    public static function nominalByPlan(string $plan): int
    {
        return match ($plan) {
            'personal'    => 12000,
            'profesional' => 25000,
            default       => 0,
        };
    }
}